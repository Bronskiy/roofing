<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Redirect;
use Schema;
use App\Mailbox;
use App\Accounts;
use App\Http\Requests\CreateMailboxRequest;
use App\Http\Requests\UpdateMailboxRequest;
use Illuminate\Http\Request;
use Webklex\IMAP\Facades\Client;


class MailboxController extends Controller {

	/**
	 * Display a listing of mailbox
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
				$accounts = Accounts::pluck("name", "id")->prepend('Choose account', 0);
        $mailbox = Mailbox::all();

		return view('admin.mailbox.index', compact('mailbox', 'accounts', 'aFolder'));
	}

	/**
	 * Show the form for creating a new mailbox
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{


	    return view('admin.mailbox.create');
	}

	/**
	 * Store a newly created mailbox in storage.
	 *
     * @param CreateMailboxRequest|Request $request
	 */
	public function store(CreateMailboxRequest $request)
	{

	//	Mailbox::create($request->all());

		return redirect()->route(config('quickadmin.route').'.mailbox.index');
	}

	/**
	 * Show the form for editing the specified mailbox.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$mailbox = Mailbox::find($id);


		return view('admin.mailbox.edit', compact('mailbox'));
	}

	/**
	 * Update the specified mailbox in storage.
     * @param UpdateMailboxRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateMailboxRequest $request)
	{
		$mailbox = Mailbox::findOrFail($id);



		$mailbox->update($request->all());

		return redirect()->route(config('quickadmin.route').'.mailbox.index');
	}

	/**
	 * Remove the specified mailbox from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		Mailbox::destroy($id);

		return redirect()->route(config('quickadmin.route').'.mailbox.index');
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
            Mailbox::destroy($toDelete);
        } else {
            Mailbox::whereNotNull('id')->delete();
        }

        return redirect()->route(config('quickadmin.route').'.mailbox.index');
    }

}
