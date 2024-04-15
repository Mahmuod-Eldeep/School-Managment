<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Http\Resources\SubjectCollection;
use App\Http\Resources\SubjectResourse;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class SubjectController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Subject::class, 'Subject');
    }

    public function index(Request $request)
    {
        $Subject = QueryBuilder::for(Subject::class)
            ->allowedIncludes('tasks')
            ->paginate();
        return new SubjectCollection($Subject);
    }



    public function store(StoreSubjectRequest $request)
    {
        $validated = $request->validated();
        $Subject = Auth::user()->subjects()->create($validated);
        return new SubjectResourse($Subject);
    }

    public function show(Request $request, Subject $Subject)
    {
        return (new SubjectResourse($Subject))
            ->load('tasks');
    }

    public function update(UpdateSubjectRequest $request, Subject $Subject)
    {
        $validated = $request->validated();
        $Subject->update($validated);
        return new SubjectResourse($Subject);
    }

    public function destroy(Request $request, Subject $Subject)
    {
        $Subject->delete();

        return response()->noContent();
    }
}
