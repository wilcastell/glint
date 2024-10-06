<?php

namespace App\Controllers;

use App\Http\Request;
use App\Models\User;
use Lib\Validator;
use Lib\Password;
use Lib\Auth;
use App\Mail\Mailer;
use Illuminate\Support\Str;
use Illuminate\Database\Capsule\Manager as DB;
use App\Exceptions\InvalidTokenException;

/**
 * Controlador de autenticación
 *
 * Este controlador maneja todas las operaciones relacionadas con la autenticación de usuarios,
 * incluyendo el registro, inicio de sesión, restablecimiento de contraseña, etc.
 */
class AuthController extends Controller
{

    protected $token;
    const LOGIN = 'login';
    const RESET = 'password/reset/';
    const REGISTER = 'register';
    const SUCCESS = 'success';
    const LOGOUT = 'logout';
    const HOME = 'home';
    const EMAIL = 'password/email';

    /**
     * Muestra el formulario de inicio de sesión.
     *
     * @return $this->view
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            redirect(self::HOME);
        }

        $this->handleErrors();
        return $this->view('auth.login');
    }

    /**
     * Muestra el formulario de registro.
     *
     * @return $this->view
     */
    public function showRegisterForm()
    {
        $this->handleErrors();
        return $this->view('auth.register');
    }

    /**
     * Maneja el registro de un nuevo usuario.
     *
     * @param Request $request
     * @return void
     */
    public function register(Request $request)
    {
        if (!verifyCsrfToken($request->input('token_csrf'))) {
            redirect(self::REGISTER)->with('error', 'Token CSRF inválido');
        }

        $rules = [
            'name' => "required|min:3|max:50",
            'email' => "required|email|unique:users,email",
            'password' => "required|min:6|confirmed",
            'role' => "required|in:user,admin"
        ];

        $messages = [
            'name.required' => 'El {field} es obligatorio',
            'name.min' => 'El {field} debe tener al menos 3 caracteres',
            'name.max' => 'El {field} es demasiado largo',
            'password.confirmed' => 'La confirmación de la contraseña no coincide',
            'email.unique' => 'El Email ya está registrado',
        ];

        $attributes = [
            'name' => 'Nombre de usuario',
            'email' => 'Email',
            'password' => 'Contraseña'
        ];

        $validator = new Validator($request->all(), $rules, $messages, $attributes);

        if ($validator->fails()) {
            $_SESSION['old'] = $request->all();
            $_SESSION['errors'] = $validator->errors();
            redirect(self::REGISTER)->with('error', $validator->errors());
        }

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Password::make($request->input('password')),
            'role' => $request->input('role')
        ]);

        unset($_SESSION['errors']);
        unset($_SESSION['old']);

        redirect(self::SUCCESS)
            ->with('success', "El usuario <strong> {$user->name}, </strong> se ha creado exitosamente.");
    }

    /**
     * Muestra la vista de éxito después de un registro o acción exitosa.
     *
     * @return $this->view
     */
    public function success()
    {
        return $this->view('auth.success');
    }

    /**
     * Maneja el inicio de sesión de un usuario.
     *
     * @param Request $request
     * @return void
     */
    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $messages = [
            'email.required' => 'El campo email es obligatorio',
            'email.email' => 'Debes colocar una dirección de email válida',
            'password.required' => 'El campo contraseña no puede quedar vacío',
        ];

        $validator = new Validator($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $_SESSION['errors'] = $validator->errors();
            $_SESSION['old'] = $request->all();
            redirect(self::LOGIN)->with('error', $validator->errors());
        }

        $user = User::where('email', $request->input('email'))->first();

        if ($user && Password::verify($request->input('password'), $user->password)) {
            Auth::login($user);
            unset($_SESSION['errors']);
            redirect(self::HOME);
        }

        redirect(self::LOGIN)->with('error', 'Credenciales incorrectas');
    }

    /**
     * Muestra la vista de acceso no autorizado.
     *
     * @return $this->view
     */
    public function unauthorized()
    {
        return $this->view('auth.unauthorized');
    }

    /**
     * Muestra la vista del popup de cierre de sesión.
     *
     * @return $this->view
     */
    public function logoutpopup()
    {
        return $this->view('auth.logoutpopup');
    }

    /**
     * Maneja el cierre de sesión de un usuario.
     *
     * @return void
     */
    public function logout()
    {
        session_unset();
        session_destroy();
        redirect(self::LOGIN);
    }

    /**
     * Muestra el formulario de solicitud de restablecimiento de contraseña.
     *
     * @return $this->view
     */
    public function showResetRequestForm()
    {
        $errors = $_SESSION['errors'] ?? [];
        if (!is_array($errors)) {
            $errors = [];
        }
        return $this->view('auth.passwords.email', ['errors' => $errors]);
    }


    /**
     * Envía un enlace de restablecimiento de contraseña al correo electrónico del usuario.
     *
     * @param Request $request
     * @return void
     */
    public function sendResetLinkEmail(Request $request)
    {
        $rules = [
            'email' => 'required|email|exists:users,email',
        ];

        $messages = [
            'email.required' => 'El email es obligatorio',
            'email.exists' => 'El correo electrónico no está registrado.',
        ];

        $validator = new Validator($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $_SESSION['errors'] = $validator->errors();
            redirect(self::EMAIL)->with('error', $validator->errors());
        }

        $token = Str::random(60);
        $email = $request->input('email');

        DB::table('password_resets')->where('email', $email)->delete();
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => now(),
        ]);

        $this->sendResetEmail($email, $token);

        redirect(self::SUCCESS)
            ->with('success', 'Te hemos enviado un enlace para restablecer tu contraseña.');
    }

    /**
     * Envía el correo electrónico de restablecimiento de contraseña.
     *
     * @param string $email
     * @param string $token
     * @return void
     */
    private function sendResetEmail($email, $token)
    {
        $mailer = new Mailer();
        $subject = 'Restablecer contraseña';
        $resetLink = getenv('APP_URL') . getenv('BASE_URL') . self::RESET . $token;

        $body = $this->view('emails.password_reset', [
            'token' => $token,
            'email' => $email,
            'resetLink' => $resetLink
        ]);

        $mailer->send($email, $subject, $body);
    }

    /**
     * Muestra el formulario de restablecimiento de contraseña.
     *
     * @param Request $request
     * @param string $token
     * @return $this->view
     * @throws InvalidTokenException
     */
    public function showResetForm(Request $request, $token)
    {
        $errors = $_SESSION['errors'] ?? [];
        if (!is_array($errors)) {
            $errors = [];
        }

        $tokenData = DB::table('password_resets')->where('token', $token)->first();

        if (!$tokenData) {
            throw new InvalidTokenException("Invalid token");
        }

        $email = $tokenData->email;

        return $this->view('auth.passwords.reset', ['token' => $token, 'email' => $email, 'errors' => $errors]);
    }

    /**
     * Maneja el restablecimiento de la contraseña del usuario.
     *
     * @param Request $request
     * @return void
     */
    public function resetPassword(Request $request)
    {
        if ($this->validateResetRequest($request)) {
            $this->handleReset($request);
        } else {
            redirect(self::RESET . $request->input('token'))->with('error', $_SESSION['errors']);
        }
    }

    /**
     * Valida la solicitud de restablecimiento de contraseña.
     *
     * @param Request $request
     * @return bool
     */
    private function validateResetRequest(Request $request)
    {
        $rules = [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
            'token' => 'required'
        ];

        $validator = new Validator($request->all(), $rules);

        if ($validator->fails()) {
            $_SESSION['errors'] = $validator->errors();
            return false;
        }

        return true;
    }

    /**
     * Maneja la lógica de restablecimiento de contraseña.
     *
     * @param Request $request
     * @return bool
     */
    private function handleReset(Request $request)
    {
        $token = $request->input('token');
        $email = $request->input('email');

        $tokenData = DB::table('password_resets')->where('token', $token)->where('email', $email)->first();

        if (!$tokenData) {
            $_SESSION['errors'] = ['token' => 'El token es inválido o ha expirado.'];
            return false;
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            $_SESSION['errors'] = ['email' => 'No se ha encontrado ningún usuario con esa dirección de correo.'];
            return false;
        }

        $user->password = Password::make($request->input('password'));
        $user->save();

        DB::table('password_resets')->where('email', $email)->delete();

        Auth::login($user);

        $_SESSION['success'] = 'Tu contraseña ha sido restablecida correctamente.';
        redirect(self::HOME);
        return true;
    }

    /**
     * Maneja los errores de autenticación.
     *
     * @param mixed $errors
     * @return void
     */
    public function handleErrors($errors = null)
    {
        if ($errors) {
            redirect(self::LOGIN)->with('error', $errors);
        }
    }
}
