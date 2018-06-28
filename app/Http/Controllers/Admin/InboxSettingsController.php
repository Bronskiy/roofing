<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\InboxSettings;
use App\Http\Requests\CreateInboxSettingsRequest;
use App\Http\Requests\UpdateInboxSettingsRequest;
use Illuminate\Http\Request;

use App\Accounts;


class InboxSettingsController extends Controller {

	/**
	 * Display a listing of inboxsettings
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $inboxsettings = InboxSettings::with("accounts")->get();

		return view('admin.inboxsettings.index', compact('inboxsettings'));
	}

	/**
	 * Show the form for creating a new inboxsettings
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    $accounts = Accounts::pluck("name", "id")->prepend('Please select', 0);

	    
	    return view('admin.inboxsettings.create', compact("accounts"));
	}

	/**
	 * Store a newly created inboxsettings in storage.
	 *
     * @param CreateInboxSettingsRequest|Request $request
	 */
	public function store(CreateInboxSettingsRequest $request)
	{
	    
		InboxSettings::create($request->all());

		return redirect()->route(config('quickadmin.route').'.inboxsettings.index');
	}

	/**
	 * Show the form for editing the specified inboxsettings.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$inboxsettings = InboxSettings::find($id);
	    $accounts = Accounts::pluck("name", "id")->prepend('Please select', 0);

	    
		return view('admin.inboxsettings.edit', compact('inboxsettings', "accounts"));
	}

	/**
	 * Update the specified inboxsettings in storage.
     * @param UpdateInboxSettingsRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateInboxSettingsRequest $request)
	{
		$inboxsettings = InboxSettings::findOrFail($id);

        

		$inboxsettings->update($request->all());

		return redirect()->route(config('quickadmin.route').'.inboxsettings.index');
	}

	/**
	 * Remove the specified inboxsettings from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		InboxSettings::destroy($id);

		return redirect()->route(config('quickadmin.route').'.inboxsettings.index');
	}

    /**
     * Mass delete function from index page
     * @param Request $request
     *
     * @return mixed
     */
    public function massDelete(Request $request)
    {
        if ($request->get('toDelete') != 'mass') {
            $toDelete = json_decode($request->get('toDelete'));
            InboxSettings::destroy($toDelete);
        } else {
            InboxSettings::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickadmin.route').'.inboxsettings.index');
    }

}
