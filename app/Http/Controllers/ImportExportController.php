<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Pet;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Response;
use App\Models\Product;

class ImportExportController extends Controller
{
    public function index()
    {
        return view('import_export.index');
    }

    public function exportClients(): StreamedResponse
    {
        $user = Auth::user();
        $companyId = $user->company_id;

        $clients = Client::where('company_id', $companyId)->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="clients.csv"',
        ];

        $callback = function () use ($clients) {
            $handle = fopen('php://output', 'w');

            if ($clients->isEmpty()) {
                fputcsv($handle, ['No data found']);
                fclose($handle);
                return;
            }

            $columns = array_keys($clients->first()->getAttributes());
            fputcsv($handle, $columns);

            foreach ($clients as $client) {
                fputcsv($handle, array_map(fn($col) => $client->$col, $columns));
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPets(): StreamedResponse
    {
        $user = Auth::user();
        $companyId = $user->company_id;

        $pets = Pet::whereHas('client', function ($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->with('client')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="pets.csv"',
        ];

        $callback = function () use ($pets) {
            $handle = fopen('php://output', 'w');

            if ($pets->isEmpty()) {
                fputcsv($handle, ['No data found']);
                fclose($handle);
                return;
            }

            $petColumns = array_keys($pets->first()->getAttributes());
            $clientNameColumns = ['client_first_name', 'client_last_name'];
            fputcsv($handle, array_merge($petColumns, $clientNameColumns));

            foreach ($pets as $pet) {
                $row = [];
                foreach ($petColumns as $col) {
                    $row[] = $pet->$col;
                }
                $row[] = $pet->client->first_name ?? '';
                $row[] = $pet->client->last_name ?? '';
                fputcsv($handle, $row);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportServices(): StreamedResponse
    {
        $user = Auth::user();
        $companyId = $user->company_id;

        $services = Service::where('company_id', $companyId)->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="services.csv"',
        ];

        $callback = function () use ($services) {
            $handle = fopen('php://output', 'w');

            if ($services->isEmpty()) {
                fputcsv($handle, ['No data found']);
                fclose($handle);
                return;
            }

            $columns = array_keys($services->first()->getAttributes());
            fputcsv($handle, $columns);

            foreach ($services as $service) {
                $row = [];
                foreach ($columns as $col) {
                    $row[] = $service->$col;
                }
                fputcsv($handle, $row);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importClients(Request $request)
{
    Log::info('--- importClients initiated ---');

    $request->validate([
        'import_file' => 'required|file|mimes:csv,txt',
    ]);

    $user = Auth::user();
    $companyId = $user->company_id;

    $file = $request->file('import_file');
    Log::info('Import file received', ['filename' => $file->getClientOriginalName()]);

    $handle = fopen($file->getRealPath(), 'r');
    $header = array_filter(fgetcsv($handle)); // Filter out any empty headers

    Log::info('CSV header parsed', ['header' => $header]);

    DB::beginTransaction();
    Log::info('Transaction started');

    try {
        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);
            Log::info('Processing row', ['data' => $data]);

            $client = null;
            if (!empty($data['id'])) {
                $client = Client::where('company_id', $companyId)->find($data['id']);
            }

            $fields = ['first_name', 'last_name', 'phone', 'email', 'address', 'city', 'state', 'postal_code'];
            $filteredData = array_intersect_key($data, array_flip($fields));

            if ($client) {
                Log::info('Updating existing client', ['id' => $client->id]);
                $client->fill($filteredData);
                $client->save();
            } else {
                Log::info('Creating new client', ['data' => $filteredData]);
                Client::create(array_merge(
                    ['company_id' => $companyId],
                    $filteredData
                ));
            }
        }

        DB::commit();
        Log::info('Client import completed successfully');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Client import failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
    }

    return redirect()->back()->with('success', 'Clients imported successfully.');
}


    public function importPets(Request $request): RedirectResponse
{
    Log::info('--- importPets initiated ---');

    $request->validate([
        'import_file' => 'required|file|mimes:csv,txt',
    ]);

    $user = Auth::user();
    $companyId = $user->company_id;

    $file = $request->file('import_file');
    Log::info('Import file received', ['filename' => $file->getClientOriginalName()]);

    $handle = fopen($file->getRealPath(), 'r');
    $header = fgetcsv($handle);
    $header = array_map(fn($h) => trim($h), $header); // Trim whitespace
    $header = array_filter($header); // Remove blanks
    
    Log::info('CSV header parsed', ['header' => $header]);

    DB::beginTransaction();
    Log::info('Transaction started');

    try {
        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);
            Log::info('Processing row', ['data' => $data]);

            if (empty($data['client_id'])) {
                Log::warning('Skipped row: missing client_id', $data);
                continue;
            }

            $client = Client::where('company_id', $companyId)
                ->where('id', $data['client_id'])
                ->first();

            if (!$client) {
                Log::warning('Skipped row: client not found or does not belong to this company', $data);
                continue;
            }

            $pet = null;
            if (!empty($data['id'])) {
                $pet = Pet::whereHas('client', function ($query) use ($companyId) {
                    $query->where('company_id', $companyId);
                })->find($data['id']);
            }

            // Define the fields to use
            $fields = ['client_id', 'name', 'species', 'breed', 'color', 'birthdate', 'notes', 'inactive'];
            $filteredData = array_intersect_key($data, array_flip($fields));

            // Parse and format birthdate safely
            $rawBirthdate = trim($filteredData['birthdate'] ?? '');

            if ($rawBirthdate !== '') {
                try {
                    $filteredData['birthdate'] = \Carbon\Carbon::createFromFormat('n/j/Y', $rawBirthdate)->format('Y-m-d');
                } catch (\Exception $e) {
                    Log::warning('Invalid birthdate format', ['raw' => $rawBirthdate]);
                    $filteredData['birthdate'] = null;
                }
            } else {
                $filteredData['birthdate'] = null;
            }

            if ($pet) {
                Log::info('Updating existing pet', ['id' => $pet->id]);
                $pet->fill($filteredData);
                $pet->save();
            } else {
                Log::info('Creating new pet', ['data' => $filteredData]);
                Pet::create($filteredData);
            }
        }

        DB::commit();
        Log::info('Pet import completed successfully');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Pet import failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
    }

    return redirect()->back()->with('success', 'Pets imported successfully.');
}

public function importServices(Request $request): \Illuminate\Http\RedirectResponse
{
    Log::info('--- importServices initiated ---');

    $request->validate([
        'import_file' => 'required|file|mimes:csv,txt',
    ]);

    $user = Auth::user();
    $companyId = $user->company_id;

    $file = $request->file('import_file');
    Log::info('Import file received', ['filename' => $file->getClientOriginalName()]);

    $handle = fopen($file->getRealPath(), 'r');
    $header = fgetcsv($handle);
    $header = array_filter($header); // Remove empty column names

    Log::info('CSV header parsed', ['header' => $header]);

    DB::beginTransaction();
    Log::info('Transaction started');

    try {
        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);
            Log::info('Processing row', ['data' => $data]);

            $service = null;
            if (!empty($data['id'])) {
                $service = \App\Models\Service::where('company_id', $companyId)->find($data['id']);
            }

            $fields = ['name', 'duration', 'price'];
            $filteredData = array_intersect_key($data, array_flip($fields));

            if ($service) {
                Log::info('Updating existing service', ['id' => $service->id]);
                $service->fill($filteredData);
                $service->save();
            } else {
                Log::info('Creating new service', ['data' => $filteredData]);
                \App\Models\Service::create(array_merge(
                    ['company_id' => $companyId],
                    $filteredData
                ));
            }
        }

        DB::commit();
        Log::info('Service import completed successfully');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Service import failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
    }

    return redirect()->back()->with('success', 'Services imported successfully.');
}

public function exportProducts()
{
    $user = auth()->user();
    $products = Product::where('company_id', $user->company_id)->get();

    $csvData = [];
    $csvData[] = ['Name', 'UPC', 'SKU', 'Description', 'Cost', 'Price', 'Quantity', 'Inactive', 'Taxable'];


    foreach ($products as $product) {
        $csvData[] = [
            $product->name,
            $product->upc,
            $product->sku,
            $product->description,
            $product->cost,
            $product->price,
            $product->quantity,
            $product->inactive,
            $product->taxable ? '1' : '0',
        ];
    }

    $output = fopen('php://temp', 'r+');
    foreach ($csvData as $row) {
        fputcsv($output, $row);
    }
    rewind($output);
    $csv = stream_get_contents($output);
    fclose($output);

    $filename = 'products_export_' . now()->format('Y-m-d_H-i-s') . '.csv';

    return Response::make($csv, 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename={$filename}",
    ]);
}

public function importProducts(Request $request)
{
    $request->validate([
        'import_file' => 'required|file|mimes:csv,txt',
    ]);

    $file = $request->file('import_file');
    $user = auth()->user();
    $handle = fopen($file, 'r');

    // Read first line and remove BOM if present
    $rawHeaderLine = fgets($handle);
    $rawHeaderLine = preg_replace('/^\xEF\xBB\xBF/', '', $rawHeaderLine); // ← this line removes BOM
    $rawHeader = str_getcsv($rawHeaderLine);

    \Log::info('Sanitized CSV header:', $rawHeader);

    // Normalize header by trimming spaces
    $header = array_map('trim', $rawHeader);

    $required = ['Name', 'UPC', 'Cost', 'Price', 'Quantity']; // Taxable is optional

    $missing = array_diff($required, $header);

    if (!empty($missing)) {
        return redirect()->back()->with('error', 'Missing required columns: ' . implode(', ', $missing));
    }

    $updated = 0;
    $created = 0;

    while (($row = fgetcsv($handle)) !== false) {
        $productData = array_combine($header, $row);

        $product = Product::firstOrNew([
            'company_id' => $user->company_id,
            'name' => $productData['Name'],
        ]);

        $product->fill([
            'upc' => $productData['UPC'] ?? null,
            'sku' => $productData['SKU'] ?? null,
            'description' => $productData['Description'] ?? null,
            'cost' => $productData['Cost'] ?? 0,
            'price' => $productData['Price'] ?? 0,
            'quantity' => $productData['Quantity'] ?? 0,
            'inactive' => $productData['Inactive'] ?? 0,
            'taxable' => isset($productData['Taxable']) ? (bool) $productData['Taxable'] : true,
        ]);
        
        $product->company_id = $user->company_id;
        $product->save();

        $product->wasRecentlyCreated ? $created++ : $updated++;
    }

    fclose($handle);

    return redirect()->back()->with('success', "Imported {$created} new products, updated {$updated} existing ones.");
}

}
