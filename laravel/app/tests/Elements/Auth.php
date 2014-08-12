<?php namespace Test\Elements;

class Auth
{
    
    public static $loginUrl = '/auth/login';
    public static $loginForm = '#frm-login';
    public static $loginRegisterLink = '#link-register';
    public static $loginForgotLink = '#link-forgot';

    public static $registerUrl = '/auth/register';
    public static $registerForm = '#frm-register';
    public static $registerLinkLogin = '#link-login';
    
    public static $forgotPasswordUrl = '/auth/forgot-password';
}
