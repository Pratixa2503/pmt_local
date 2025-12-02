<?php 
use Illuminate\Support\Facades\DB;
use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;
Broadcast::channel('conversation.{conversationId}', fn($user,$cid) => ['id'=>$user->id]);

// Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
//     $conv = Conversation::find($conversationId);
//     if (!$conv) return false;

//     $projectId = (int) $conv->project_id;

//     // Super admin shortcut (adjust to your roles check)
//     if (method_exists($user, 'hasRole') && $user->hasRole('super admin')) {
//         return ['id' => $user->id, 'name' => $user->first_name ?? $user->name];
//     }

//     $isPM = DB::table('project_user')->where('project_id', $projectId)->where('user_id', $user->id)->exists();
//     $isMember = DB::table('project_member_assignments')->where('project_id', $projectId)->where('member_id', $user->id)->exists();
//     $inIntake = DB::table('project_intakes')
//         ->where('parent_id', $projectId)
//         ->where(function ($q) use ($user) {
//             $q->where('abstractor_id', $user->id)
//               ->orWhere('reviewer_id', $user->id)
//               ->orWhere('sense_check_ddr_id', $user->id)
//               ->orWhere('property_manager_id', $user->id);
//         })
//         ->exists();
//     $isCustomer = DB::table('contact_project')
//         ->where('project_id', $projectId)
//         ->where('contact_id', $user->id)
//         ->exists();
//     if ($isPM || $isMember || $inIntake || $isCustomer) {
//         // Return user info for presence if you switch to presence channel later
//         return ['id' => $user->id, 'name' => $user->first_name ?? $user->name];
//     }
   
//     return false;
// });
