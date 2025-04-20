<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\PromotionContact;
use App\Models\PromotionSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromotionController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:promotion-contact-list', ['only' => ['contactList']]);
        $this->middleware('permission:promotion-contact-create', ['only' => ['createContact']]);
        $this->middleware('permission:promotion-contact-edit', ['only' => ['editContact']]);
        $this->middleware('permission:promotion-contact-delete', ['only' => ['deleteContact']]);
        $this->middleware('permission:promotion-contact-restore', ['only' => ['restoreContact']]);
        $this->middleware('permission:promotion-schedule-list', ['only' => ['scheduleList']]);
        $this->middleware('permission:promotion-schedule-create', ['only' => ['createSchedule']]);
        $this->middleware('permission:promotion-schedule-edit', ['only' => ['editSchedule']]);
        $this->middleware('permission:promotion-schedule-delete', ['only' => ['deleteSchedule']]);
        $this->middleware('permission:promotion-schedule-restore', ['only' => ['restoreSchedule']]);
    }

    function contactList()
    {
        $contacts = PromotionContact::withTrashed()->latest()->get();
        return view('promotion.contact.index', compact('contacts'));
    }

    function createContact()
    {
        $branches = Branch::selectRaw("branch_name AS name, id")->pluck('name', 'id');
        return view('promotion.contact.create', compact('branches'));
    }

    function saveContact(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'contact_number' => 'required|numeric|digits:10|unique:promotion_contacts,contact_number',
            'entity' => 'required',
            'type' => 'required',
            'branch_id' => 'required',
        ]);
        $input = $request->all();
        $input['created_by'] = $request->user()->id;
        $input['updated_by'] = $request->user()->id;
        PromotionContact::create($input);
        return redirect()->route('promotion.contact.list')->with("success", "Contact created successfully!");
    }

    function editContact(string $id)
    {
        $contact = PromotionContact::findOrFail(decrypt($id));
        $branches = Branch::selectRaw("branch_name AS name, id")->pluck('name', 'id');
        return view('promotion.contact.edit', compact('contact', 'branches'));
    }

    function updateContact(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'contact_number' => 'required|numeric|digits:10|unique:promotion_contacts,contact_number,' . $id,
            'entity' => 'required',
            'type' => 'required',
            'branch_id' => 'required',
        ]);
        $input = $request->all();
        $input['updated_by'] = $request->user()->id;
        PromotionContact::findOrFail($id)->update($input);
        return redirect()->route('promotion.contact.list')->with("success", "Contact updated successfully!");
    }

    function deleteContact(string $id)
    {
        PromotionContact::findOrFail(decrypt($id))->delete();
        return redirect()->route('promotion.contact.list')->with("success", "Contact deleted successfully!");
    }

    function restoreContact(string $id)
    {
        PromotionContact::withTrashed()->where('id', decrypt($id))->restore();
        return redirect()->route('promotion.contact.list')->with("success", "Contact restored successfully!");
    }

    function scheduleList()
    {
        $schedules = PromotionSchedule::withTrashed()->latest()->get();
        return view('promotion.schedule.index', compact('schedules'));
    }

    function createSchedule()
    {
        $branches = Branch::selectRaw("branch_name AS name, id")->union(DB::table('branches')->selectRaw("'All' AS name, 0 AS id"))->pluck('name', 'id');
        return view('promotion.schedule.create', compact('branches'));
    }

    function saveSchedule(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'scheduled_date' => 'required|date|unique:promotion_schedules,scheduled_date',
            'template_id' => 'required',
            'entity' => 'required',
            'sms_limit_per_hour' => 'required|numeric|min:1',
            'branch_id' => 'required',
        ]);
        $input = $request->all();
        $input['created_by'] = $request->user()->id;
        $input['updated_by'] = $request->user()->id;
        PromotionSchedule::create($input);
        return redirect()->route('promotion.schedule.list')->with("success", "Promotion scheduled successfully!");
    }

    function editSchedule(string $id)
    {
        $schedule = PromotionSchedule::findOrFail(decrypt($id));
        $branches = Branch::selectRaw("branch_name AS name, id")->union(DB::table('branches')->selectRaw("'All' AS name, 0 AS id"))->pluck('name', 'id');
        return view('promotion.schedule.edit', compact('schedule', 'branches'));
    }

    function updateSchedule(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'scheduled_date' => 'required|date|unique:promotion_schedules,scheduled_date,' . $id,
            'template_id' => 'required',
            'entity' => 'required',
            'sms_limit_per_hour' => 'required|numeric|min:1',
            'branch_id' => 'required',
        ]);
        $input = $request->all();
        $input['updated_by'] = $request->user()->id;
        PromotionSchedule::findOrFail($id)->update($input);
        return redirect()->route('promotion.schedule.list')->with("success", "Promotion schedule updated successfully!");
    }

    function deleteSchedule(string $id)
    {
        PromotionSchedule::findOrFail(decrypt($id))->delete();
        return redirect()->route('promotion.schedule.list')->with("success", "Promotion schedule deleted successfully!");
    }

    function restoreSchedule(string $id)
    {
        PromotionSchedule::withTrashed()->where('id', decrypt($id))->restore();
        return redirect()->route('promotion.schedule.list')->with("success", "Schedule restored successfully!");
    }
}
