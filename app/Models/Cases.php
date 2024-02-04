<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cases extends Model
{
    use HasFactory;

    protected $table = 'cases';
    protected $fillable = [
        'court',
        'highcourt',
        'bench',
        'casetype',
        'casenumber',
        'diarybumber',
        'year',
        'case_number',
        'filing_date',
        'filing_date',
        'floor',
        'title',
        'description',
        'before_judges',
        'referred_by',
        'section',
        'priority',
        'under_acts',
        'under_sections',
        'FIR_police_station',
        'FIR_number',
        'FIR_year',
        'your_advocates',
        'your_team',
        'opponents',
        'opponent_advocates',
        'filing_party',
        'case_status',
        'journey',
        'advocates',
        'judge',
        'police_station',
        'your_party_name',
        'opp_party_name',
        'stage',
        'opp_adv',
        'case_docs',
    ];

    public static function getCasesById($id){

        $cases = Cases::whereIn('id',explode(',',$id))->pluck('title')->toArray();
        return implode(',',$cases);
    }

    public static function caseType(){
        $types = [
            'Arbitration Petition' => 'Arbitration Petition',
            'Civil Appeal' => 'Civil Appeal',
            'Contempt Petition (Civil)' => 'Contempt Petition (Civil)',
            'Contempt Petition (Criminal)' => 'Contempt Petition (Criminal)',
            'Criminal Appeal' => 'Criminal Appeal',
            'Curative Petition(Civil)' => 'Curative Petition(Civil)',
            'Curative Petition(Criminal)' => 'Curative Petition(Criminal)',
            'Criminal Case' => 'Criminal Case',
            'DEATH REFERENCE CASE' => 'DEATH REFERENCE CASE',
            'DIARY NO.' => 'DIARY NO.',
            'DIARYNO AND DIARYYR' => 'DIARYNO AND DIARYYR',
            'Election Petition (Civil)' => 'Election Petition (Civil)',
            'FILE NUMBER' => 'FILE NUMBER',
            'MISCELLANEOUS APPLICATION' => 'MISCELLANEOUS APPLICATION',
            'Motion Case(Crl.)' => 'Motion Case(Crl.)',
            'Original Suit' => 'Original Suit',
            'Probate Case' => 'Probate Case',
            'Family Law Case' => 'Family Law Case',
            'Workers\' Compensation Case' => 'Workers\' Compensation Case',
            'Intellectual Property Case' => 'Intellectual Property Case',
            'REF. U/A 317(1)' => 'REF. U/A 317(1)',
            'REF. U/S 14 RTI' => 'REF. U/S 14 RTI',
            'REF. U/S 143' => 'REF. U/S 143',
            'REF. U/S 17 RTI' => 'REF. U/S 17 RTI',
            'Review Petition (Civil)' => 'Review Petition (Civil)',
            'Review Petition (Criminal)' => 'Review Petition (Criminal)',
            'SLP (Civil)' => 'SLP (Civil)',
            'SLP (Criminal)' => 'SLP (Criminal)',
            'SPECIAL LEAVE TO PETITION (CIVIL)' => 'SPECIAL LEAVE TO PETITION (CIVIL)',
            'SPECIAL LEAVE TO PETITION (CRIMINAL)' => 'SPECIAL LEAVE TO PETITION (CRIMINAL)',
            'Special Reference Case' => 'Special Reference Case',
            'Suo-Moto Contempt Pet.(Civil) D' => 'Suo-Moto Contempt Pet.(Civil) D',
            'Suo-Moto Contempt Pet.(Criminal) D' => 'Suo-Moto Contempt Pet.(Criminal) D',
            'Suo-Moto W.P(Civil) D' => 'Suo-Moto W.P(Civil) D',
            'Suo-Moto W.P(Criminal) D' => 'Suo-Moto W.P(Criminal) D',
            'Tax Reference Case' => 'Tax Reference Case',
            'Tranfer Case (Civil)' => 'Tranfer Case (Civil)',
            'Transfer Case (Criminal)' => 'Transfer Case (Criminal)',
            'Transfer Petition (Civil)' => 'Transfer Petition (Civil)',
            'Transfer Petition (Criminal)' => 'Transfer Petition (Criminal)',
            'Writ Petition (Civil)' => 'Writ Petition (Civil)',
            'Writ Petition(Criminal)' => 'Writ Petition(Criminal)',
            'WRIT TO PETITION (CIVIL)' => 'WRIT TO PETITION (CIVIL)',
            'WRIT TO PETITION (CRIMINAL)' => 'WRIT TO PETITION (CRIMINAL)',
        ];
        return $types;
    }

    public static function casePriority(){
        return [
            'Super Critical' => 'Super Critical',
            'Critical' => 'Critical',
            'Important' => 'Important',
            'Routine' => 'Routine',
            'Normal' => 'Normal',
        ];
    }

    public static function caseJourney(){
        return [
            'Client Intake Phase',
            'Case Assessment and Strategy Phase',
            'Pleadings and Discovery Phase',
            'Motion Practice and Pre-Trial Phase',
            'Trial Phase',
            'Post-Trial Phase',
            'Case Closure Phase',
            'Client initial Consultation',
            'Case evaluation',
            'Draft Court Pleadings (Complaint, Answer, Motions)',
            'Prepare and argue motions',
            'Prepare trial strategy',
            'Review verdict & judgement',
            'Finalize the settlement (if applicable)',
            'Gather client information',
            'Conduct legal research',
            'Court filings',
            'Prepare for court hearings',
            'Draft trial brief',
            'Initiate enforcement actions',
            'Verifying case status',
            'Assess the case',
            'Analyze relevant laws',
            'Serve/respond to discovery request',
            'Attend court pre-trial conferences',
            'Examine witnesses',
            'Filling post trial motions (verdict, motion for new trial)',
            'Comply/ Execute the court order',
            'Sign the contract with client',
            'Analyze precedents & similar cases',
            'Engage in settlement and negotiation',
            'Pre-trial activities (deposition, witness preparation)',
            'Prepare and present evidences',
            'Ensure compliance for court  verdict obligations',
            'Close all Admin Tasks',
            'Sign the engagement letter',
            'Assess strength and weaknesses',
            'Case Docketing and Scheduling',
            'Witnesses Preparation',
            'Prepare opening and closing arguments',
            'Need for appeal assessment',
            'Close the case file Archiving case files',
            'Collect the required documents',
            'Develop case strategy',
            'Discovery Planning & Requests',
            'Identify evidence & exhibits',
            'Attend the court proceedings',
            'Prepare appellate briefs',
            'Close the client- attorney relation',
            'Assign the working team',
            'Discuss legal advice with customer',
            'Witnesses and Experts Depositions',
            'Trial Preparation',
            'Respond to opposing counsel',
            'Presenting customer for appellate',
            'Case final review and audit',
        ];
    }

    public function motion($id)
    {

        return Motion::where('id',$id)->value('type');
    }
    public function getCourt() {
        return $this->belongsTo(Court::class, 'court', 'id');
    }

}
