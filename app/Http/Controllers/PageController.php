<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function page(Request $request)
    {
        $uri = trim($request->getRequestUri(), '/)');
        $viewName = 'custom.' . str_replace('/', '.', $uri);
        if (!$this->isValid($viewName)) {
            return redirect()->route('404');
        }
        return view($viewName);
    }

    private function isValid(string $viewName)
    {
        return $viewName != "custom.pages.master" && view()->exists($viewName);
    }
}
