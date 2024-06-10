<?php

namespace App\Livewire\FinancialReporting;

use App\Models\ReportNote;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Notes extends Component
{
    public $reportId;
    public $reportType;
    public $reportName;
    public $note;
    public $notes;
    public $numUnread = 0;

    protected $rules = [
        "note" => "required|max:120",
    ];

    public function mount(string $reportId, string $reportType, string $reportName){
        $this->reportId = $reportId;
        $this->reportType = $reportType;
        $this->reportName = $reportName;
    }
    
    public function countUnread($notes){
        $this->numUnread = 0;
        $user = auth()->user()->id;
        
        foreach($notes as $note){
            $participants = json_decode($note->participants, true);
            if(!in_array($user, $participants)){
                $this->numUnread += 1;
            }
        }
    }

    public function add(){
        $this->validate();

        $firstName = auth()->user()->first_name;
        $lastName = auth()->user()->last_name;
        $participants = array(auth()->user()->id);
 
        DB::table("report_notes")->insert([
            "tb_id" => $this->reportType === "tb" ? $this->reportId : null,
            "collection_id" => $this->reportType === "fsc" ? $this->reportId : null,
            "participants" => json_encode($participants, true),
            "content" => $this->note,
            "author" => "$firstName $lastName"
        ]);

        $user = $firstName . " " . $lastName;
        activity()->withProperties(['user' => $user, 'role' => auth()->user()->role])->log("Created a note content: $this->note");
        $this->note = null;
    }

    public function update(){
        if($this->numUnread == 0){
            return;
        }

        $user = auth()->user()->id;

        foreach($this->notes as $note){
            $participants = json_decode($note->participants, true);
            if(!in_array($user, $participants)){
                $participants[] = $user;
                $note->participants = json_encode($participants, true);
                $note->save();
            }
        }
    }

    public function delete(int $noteIndex){
        if($this->notes->isEmpty()) {
            return;
        }

        $firstName = auth()->user()->first_name;
        $lastName = auth()->user()->last_name;

        $note = $this->notes[$noteIndex];
        DB::table("report_notes")->where("note_id", "=", $note->note_id)->delete();

        $user = $firstName . " " . $lastName;
        activity()->withProperties(['user' => $user, 'role' => auth()->user()->role])->log("Deleted note content: $note->content");
    }

    public function render()
    {
        $this->notes = ReportNote::select("note_id","content", "author", "participants", "created_at")
                        ->where("tb_id", "=", $this->reportId)
                        ->orwhere("collection_id", "=", $this->reportId)
                        ->get();
        
        $this->countUnread($this->notes);

        return view('livewire.financial-reporting.notes', ["notes" => $this->notes, "numNotes" => count($this->notes),"numUnread" => $this->numUnread, "xColor" => "#2D349A"]);
    }
}
