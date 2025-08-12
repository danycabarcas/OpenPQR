
<?php
// app/Http/Controllers/Public/SignupController.php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Department;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SignupController extends Controller
{
    public function create()
    {
        return view('public.signup');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_name'  => ['required','string','max:160'],
            'sector'        => ['nullable','string','max:120'],
            'email'         => ['required','email','max:150','unique:users,email'],
            'phone'         => ['nullable','string','max:40'],
            'password'      => ['required','string','min:8','confirmed'],
            'terms'         => ['accepted'],
        ]);

        // Empresa
        $company = Company::create([
            'name'          => $data['company_name'],
            'slug'          => Str::slug($data['company_name'].'-'.Str::random(4)),
            'sector'        => $data['sector'] ?? null,
            'email_contact' => $data['email'],
            'phone_contact' => $data['phone'] ?? null,
            'is_active'     => true,
        ]);

        // Suscripción Trial 30 días a Essential
        $essential = Plan::where('slug','essential')->firstOrFail();
        Subscription::create([
            'company_id'        => $company->id,
            'plan_id'           => $essential->id,
            'is_trial'          => true,
            'status'            => 'active',
            'start_date'        => now()->toDateString(),
            'end_date'          => now()->addDays(30)->toDateString(),
            'price'             => null,
            'last_payment_date' => null,
            'next_billing_date' => null,
        ]);

        // Departamento por defecto (opcional)
        if (class_exists(Department::class)) {
            Department::create([
                'company_id' => $company->id,
                'name'       => 'Atención al Ciudadano',
                'is_active'  => true,
            ]);
        }

        // Usuario admin de la empresa
        $user = User::create([
            'company_id'   => $company->id,
            'department_id'=> null,
            'name'         => 'Admin',
            'lastname'     => 'Principal',
            'email'        => $data['email'],
            'phone'        => $data['phone'] ?? null,
            'password'     => Hash::make($data['password']),
            'is_active'    => true,
        ]);

        // Rol (Spatie): company_admin
        if (method_exists($user, 'assignRole')) {
            $user->assignRole('company_admin');
        }

        // Login directo
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', '¡Registro exitoso! Tienes 30 días de prueba en Essential.');
    }
}
