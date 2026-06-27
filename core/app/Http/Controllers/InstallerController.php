<?php

namespace App\Http\Controllers;

use App\Core\Installer\Installer;
use Illuminate\Http\Request;

class InstallerController extends Controller
{
    public function __construct(
        protected Installer $installer
    ) {}

    public function welcome()
    {
        if ($this->installer->isCompleted()) {
            return redirect('/');
        }

        return view('installer.welcome');
    }

    public function type()
    {
        if ($this->installer->isCompleted()) {
            return redirect('/');
        }

        return view('installer.type');
    }

    public function postType(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:website,ecommerce,api',
        ]);

        $this->installer->run(array_merge($validated, ['step' => 'type']));

        return redirect()->route('installer.database');
    }

    public function database()
    {
        if ($this->installer->isCompleted()) {
            return redirect('/');
        }

        return view('installer.database');
    }

    public function postDatabase(Request $request)
    {
        $validated = $request->validate([
            'driver' => 'required|in:sqlite,mysql',
        ]);

        $this->installer->run(array_merge($validated, ['step' => 'database']));

        return redirect()->route('installer.features');
    }

    public function features()
    {
        if ($this->installer->isCompleted()) {
            return redirect('/');
        }

        return view('installer.features');
    }

    public function postFeatures(Request $request)
    {
        $this->installer->run([
            'step' => 'features',
            'realtime' => $request->boolean('realtime'),
            'ai' => $request->boolean('ai'),
        ]);

        return redirect()->route('installer.admin');
    }

    public function admin()
    {
        if ($this->installer->isCompleted()) {
            return redirect('/');
        }

        return view('installer.admin');
    }

    public function postAdmin(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $this->installer->run(array_merge($validated, ['step' => 'admin']));

        return redirect()->route('installer.complete');
    }

    public function complete()
    {
        $this->installer->run(['step' => 'complete']);

        return view('installer.complete');
    }
}
