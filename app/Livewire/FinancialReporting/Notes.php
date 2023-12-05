<?php

namespace App\Livewire\FinancialReporting;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Notes extends Component
{
    public $reportId;
    public $reportType;
    public $reportName;
    public $note;
    public $notes;

    protected $rules = [
        "note" => "required|max:120",
    ];

    public function mount(string $reportId, string $reportType, string $reportName){
        $this->reportId = $reportId;
        $this->reportType = $reportType;
        $this->reportName = $reportName;
    }

    public function add(){
        $this->validate();

        $firstName = auth()->user()->first_name;
        $lastName = auth()->user()->last_name;
 
        DB::table("report_notes")->insert([
            "tb_id" => $this->reportType === "tb" ? $this->reportId : null,
            "collection_id" => $this->reportType === "col" ? $this->reportId : null,
            "content" => $this->note,
            "author" => "$firstName $lastName"
        ]);

        $this->note = null;
    }

    public function delete(int $noteIndex){
        if($this->notes->isEmpty()) {
            return;
        }

        $note = $this->notes[$noteIndex];
        DB::table("report_notes")->where("note_id", "=", $note->note_id)->delete();
    }

    public function render()
    {
        $this->notes = DB::table("report_notes")
                    ->select("note_id","content", "author", "created_at", "updated_at")
                    ->where("tb_id", "=", $this->reportId)
                    ->orwhere("collection_id", "=", $this->reportId)
                    ->get();

        return view('livewire.financial-reporting.notes', ["notes" => $this->notes, "numNotes" => count($this->notes), "xColor" => "#2D349A"]);
    }
}
