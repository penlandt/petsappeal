use App\Models\Company;
use Illuminate\View\View;

public function createWithCompany(Company $company): View
{
    return view('client.auth.login', [
        'company' => $company,
    ]);
}
