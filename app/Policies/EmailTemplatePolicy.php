<?php

namespace App\Policies;

use App\Models\User;
use App\Models\EmailTemplate;

class EmailTemplatePolicy
{
    public function view(User $user, EmailTemplate $template): bool
    {
        return $user->company_id === $template->company_id;
    }

    public function update(User $user, EmailTemplate $template): bool
    {
        return $user->company_id === $template->company_id;
    }
}
