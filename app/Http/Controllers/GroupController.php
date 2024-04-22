<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class GroupController extends Controller
{

    function upsert()
    {
        try {
            $validatedData = request()->validate([
                'name' => 'required|string',
                'id' => 'numeric'
            ]);

            if (!isset($validatedData['id'])) {
                $validatedData['id'] = -1;
            }


            $group = Group::updateOrCreate(['id' => $validatedData['id']], $validatedData);

            $msg = ($group->id === $validatedData['id']) ? 'updated' : 'added';

            return $this->success("Group " . $msg . " successfully", 201);
        } catch (ValidationException $e) {
            // Validation failed, return custom response
            return $this->error('Validation failed: ' . $e->getMessage(), 422);
        }
    }

    function get(Request $req, $id)
    {

        try {
            $group  = Group::findOrFail($id);
            return $this->success($group);
        } catch (Exception $e) {
            return $this->error('Group not found', 404);
        }
    }

    function delete(Request $req, $id)
    {
        try {

            $group  = Group::findOrFail($id);
            $group->delete();

            return $this->success("Group has been deleted successfully");
        } catch (Exception $e) {
            return $this->error('No group found to delete', 404);
        }
    }

    function addEmails(Request $request, $groupId)
    {
        $group = Group::findOrFail($groupId);
        $emailIds = $request->input('email_ids');

        $group->emails()->attach($emailIds);

        return response()->json(['message' => 'Emails attached to group successfully'], 200);
    }
}
