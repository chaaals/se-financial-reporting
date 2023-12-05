<?php

namespace App\Livewire\FinancialReporting;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Notes extends Component
{
    public $reportId;
    public $reportType;
    public $reportName;
    public $comment;

    protected $rules = [
        "comment" => "required|max:120",
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
            "content" => $this->comment,
            "author" => "$firstName $lastName"
        ]);

        $this->comment = null;
    }

    public function delete(){

    }

    public function render()
    {
        $comments = DB::table("report_notes")
                    ->select("content", "author", "created_at", "updated_at")
                    ->where("tb_id", "=", $this->reportId)
                    ->orwhere("collection_id", "=", $this->reportId)
                    ->get();

        return view('livewire.financial-reporting.notes', ["comments" => $comments, "numComments" => count($comments), "xColor" => "#2D349A"]);
    }
}
