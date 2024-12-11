<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocaleController extends Controller
{
    private $locale;
    private $redirectUrl;
    public function setLocale($locale)
    {
        $this->locale = $locale ?? App::getLocale();
        $this->redirectUrl = Session::get('_previous')['url'];
        App::setLocale($this->locale);
        Session::put('locale', $this->locale);
        return redirect($this->redirectUrl);
    }
}
