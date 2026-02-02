<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class LoginModal extends Component
{
    public bool $show = false;
    public string $view = 'login'; // 'login' or 'forgot-password'
    public string $email = '';
    public string $password = '';
    public bool $remember = false;    
    public ?string $error = null;
    
    // protected $listeners = ['open-login-modal' => 'openModal'];
    
    protected function rules()
    {
        return [
            'email' => 'required|email',
            'password' => $this->view === 'login' ? 'required' : 'nullable',
        ];
    }
    
    public function openModal()
    {
        $this->show = true;
        $this->view = 'login';
        $this->reset(['email', 'password', 'remember', 'error']);
    }
    
    public function closeModal()
    {
        $this->show = false;
        $this->reset(['email', 'password', 'remember', 'error', 'view']);
    }
    
    public function login()
    {
        $this->validate();
        
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            $this->closeModal();
            return redirect()->intended(route('member.explore'));
        }
        
        $this->error = 'Forkert email eller adgangskode.';
    }

    public function sendPasswordResetLink()
    {
        $this->validate(['email' => 'required|email']);
        
        $status = Password::sendResetLink(['email' => $this->email]);
        
        if ($status === Password::RESET_LINK_SENT) {
            session()->flash('status', __($status));
        } else {
            $this->addError('email', __($status));
        }
    }
    
    public function render()
    {
        return view('livewire.auth.login-modal');
    }
}
