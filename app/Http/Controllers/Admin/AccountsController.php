<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\Accounts;
use App\Http\Requests\CreateAccountsRequest;
use App\Http\Requests\UpdateAccountsRequest;
use Illuminate\Http\Request;



class AccountsController extends Controller {

	/**
	 * Display a listing of accounts
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $accounts = Accounts::all();

		return view('admin.accounts.index', compact('accounts'));
	}

	/**
	 * Show the form for creating a new accounts
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    
	    
	    return view('admin.accounts.create');
	}

	/**
	 * Store a newly created accounts in storage.
	 *
     * @param CreateAccountsRequest|Request $request
	 */
	public function store(CreateAccountsRequest $request)
	{
	    
		Accounts::create($request->all());

		return redirect()->route(config('quickadmin.route').'.accounts.index');
	}

	/**
	 * Show the form for editing the specified accounts.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$accounts = Accounts::find($id);
	    
	    
		return view('admin.accounts.edit', compact('accounts'));
	}

	/**
	 * Update the specified accounts in storage.
     * @param UpdateAccountsRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateAccountsRequest $request)
	{
		$accounts = Accounts::findOrFail($id);

        

		$accounts->update($request->all());

		return redirect()->route(config('quickadmin.route').'.accounts.index');
	}

	/**
	 * Remove the specified accounts from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		Accounts::destroy($id);

		return redirect()->route(config('quickadmin.route').'.accounts.index');
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
            Accounts::destroy($toDelete);
        } else {
            Accounts::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickadmin.route').'.accounts.index');
    }

}
