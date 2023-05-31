<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Property;

class AgentPropertyController extends Controller
{
  public function AgentAllProperty()
  {
    $id = Auth::user()->id;
    $property = Property::where('agent_id', $id)->latest()->get();

    return view(
      'agent.property.all_property',
      compact('property')
    );
  } // End Method 
}
