<?php

namespace Database\Seeders;

use App\Models\DocType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $doctype = [
            ['name' => 'Complaint/Petition', 'description' => 'The initial document filed by the plaintiff to initiate a lawsuit, outlining the claims against the defendant', 'created_by' =>  '2'],
            ['name' => 'Answer/Responsive Pleading', 'description' => 'The defendant\'s response to the complaint, addressing the allegations and asserting any defenses', 'created_by' => '2'],
            ['name' => 'Motion', 'description' => 'A formal request made to the court seeking a specific ruling, order, or relief. Examples include motions to dismiss, motions for summary judgment, or motions to compel discovery', 'created_by' => '2'],
            ['name' => 'Brief/Memorandum', 'description' => 'A written legal argument submitted to the court, presenting the parties\' positions and supporting legal authority on a specific issue.', 'created_by' => '2'],
            ['name' => 'Affidavit/Declaration', 'description' => 'A sworn statement of facts made by a party or witness, submitted as evidence to support or oppose a motion or other court action', 'created_by' => '2'],
            ['name' => 'Discovery Requests and Responses', 'description' => 'Documents used during the discovery phase of the litigation process, including requests for documents, interrogatories (written questions), requests for admissions, and responses to these requests', 'created_by' => '2'],
            ['name' => 'Subpoena', 'description' => 'A court order requiring a person to appear in court or produce documents or other evidence for a legal proceeding', 'created_by' => '2'],
            ['name' => 'Notice', 'description' => 'Formal written communication to inform the court or other parties of specific actions, events, or intentions', 'created_by' => '2'],
            ['name' => 'Verdict/Order/Judgment', 'description' => 'A written decision issued by the court, outlining the court\'s ruling or decision on a specific matter or the final disposition of the case.', 'created_by' => '2'],
            ['name' => 'Stipulation/Agreement', 'description' => 'A written agreement between the parties, submitted to the court for approval, outlining terms and conditions agreed upon in the case', 'created_by' => '2'],
            ['name' => 'Notice of Appearance', 'description' => 'A document filed by an attorney or law firm to officially notify the court and other parties that they are representing a party in the case', 'created_by' => '2'],
            ['name' => 'Certificate of Service', 'description' => 'A document proving that a copy of a filed document has been properly served on all required parties, in compliance with court rules and procedures.', 'created_by' => '2'],
            ['name' => 'Exhibits', 'description' => 'Supporting documents, records, or other physical or electronic evidence submitted to the court in conjunction with other documents or during court proceedings.', 'created_by' => '2'],
            ['name' => 'Transcript', 'description' => 'A written record of the verbatim proceedings of a court hearing or trial, typically prepared by a court reporter', 'created_by' => '2'],
            ['name' => 'Notice of Appeal/Appellate Brief', 'description' => 'Documents filed when a party seeks to appeal a court\'s decision, including a notice of appeal and a written argument (appellate brief) presenting the legal arguments on appeal.', 'created_by' => '2'],
            ['name' => 'Pretrial Conference Statement', 'description' => 'A document submitted by each party prior to a pretrial conference, outlining the issues in dispute, summarizing the evidence, and proposing a plan for the trial.', 'created_by' => '2'],
            ['name' => 'Notice of Deposition', 'description' => 'A document informing the opposing party of the scheduled deposition of a witness, including the date, time, and location of the deposition.', 'created_by' => '2'],
            ['name' => 'Expert Witness Report', 'description' => 'A written report prepared by an expert witness, providing their opinions, analysis, and conclusions on technical or specialized matters relevant to the case.', 'created_by' => '2'],
            ['name' => 'Settlement Agreement', 'description' => 'A document detailing the terms and conditions agreed upon by the parties to resolve the case outside of court, often submitted to the court for approval and entry as a final judgment.', 'created_by' => '2'],
            ['name' => 'Notice of Substitution of Counsel', 'description' => 'A document filed to inform the court and other parties of the change in legal representation for a party in the case.', 'created_by' => '2'],
            ['name' => 'Protective Order', 'description' => 'A court order that restricts or limits the disclosure or use of certain information or documents deemed sensitive, confidential, or privileged.', 'created_by' => '2'],
            ['name' => 'Writ of Execution', 'description' => 'A court order directing the enforcement of a judgment, typically to seize or sell property to satisfy a debt or judgment.', 'created_by' => '2'],
            ['name' => 'Appellate Opinion', 'description' => 'A written decision issued by an appellate court, providing its legal analysis and ruling on the issues raised in an appeal.', 'created_by' => '2'],
            ['name' => 'Notice of Dismissal/Withdrawal', 'description' => 'A document filed by a party to voluntarily dismiss or withdraw their claims or defenses in the case.', 'created_by' => '2'],
            ['name' => 'Notice of Change of Address', 'description' => 'A document filed to notify the court and other parties of a change in address for a party or their legal representation.', 'created_by' => '2'],
            ['name' => 'Settlement Demand', 'description' => 'A written statement or letter from one party to another, outlining their proposed terms for settlement or resolution of the case.', 'created_by' => '2'],
            ['name' => 'Notice of Intention to Introduce Evidence', 'description' => 'A document filed by a party to notify the court and opposing parties of their intention to introduce specific evidence at trial.', 'created_by' => '2'],
            ['name' => 'Notice of Continuance', 'description' => 'A document requesting the rescheduling or postponement of a court hearing, trial, or other proceedings.', 'created_by' => '2'],
            ['name' => 'Application for Default Judgment', 'description' => 'A document filed when a party fails to respond or appear within the required time, seeking a judgment in favor of the non-defaulting party.', 'created_by' => '2'],
            ['name' => 'Notice of Change of Trial Date', 'description' => 'A document filed to inform the court and other parties of a change or rescheduling of the trial date.', 'created_by' => '2'],
        ];

        DocType::insert($doctype);
    }
}
