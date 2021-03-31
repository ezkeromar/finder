<?php namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Models\User;
use App\Models\Diploma;
use App\Models\Experience;
use App\Models\UserDiploma;
use App\Models\Certification;
use App\Models\Technology;
use App\Models\Skill;
use Auth;
use Storage;
use DB;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ConsultentCrudRequest as StoreRequest;
use App\Http\Requests\ConsultentCrudRequest as UpdateRequest;

class ConsultentCrudController extends CrudController {

	public function setup() {
        $this->crud->setModel("App\Models\User");
        $this->crud->setRoute("admin/consultent");
        $this->crud->setEntityNameStrings('consultant', 'consultants');

        $this->crud->allowAccess(['list', 'create', 'delete']);

        $this->crud->setColumns(['id', 
            [ 'name' => 'firstname', 'label' => "Prénom" ], 
            [ 'name' => 'lastname', 'label' => "Nom" ], 
            [ 'name' => 'email', 'label' => "E-mail" ], 
            [ 'name' => 'profession', 'label' => "Profession" ], 
            [ 'name' => 'phone', 'label' => "Téléphone" ], 
            [ 'name' => 'admin_id', 'label' => "Ajouté par" ], 
        	['name' => 'created_at', 'label' => 'Date de création'],
        ]);

        $this->crud->orderBy('created_at','desc');

        $this->crud->addClause('where', 'client_id', '=', 0);
        $this->crud->addClause('where', 'name', '=', NULL);


        $this->crud->addField([
            'name' => 'firstname',
            'label' => "Prénom"
        ]);

        $this->crud->addField([
            'name' => 'lastname',
            'label' => "Nom"
        ]);

        $this->crud->addField([ 
            'label' => "Photo de profil",
            'name' => "picture",
            'type' => 'upload',
            'upload' => true,
        ]);

        $this->crud->addField([
            'name' => 'email',
            'label' => "E-mail"
        ]);

        $this->crud->addField([
            'name' => 'phone',
            'label' => "Téléphone"
        ]);

        $this->crud->addField([
            'name' => 'address',
            'label' => "Adresse"
        ]);

        $this->crud->addField([ 
            'name' => 'city_id',
            'label' => 'Ville',
            'type' => "select2",
            'entity' => 'city', 
            'attribute' => "title", 
            'model' => "App\Models\City",
        ]);

        $this->crud->addField([ 
            'name' => 'country_id',
            'label' => 'Nationnalité',
            'type' => "select2",
            'entity' => 'country', 
            'attribute' => "name", 
            'model' => "App\Models\Country",
        ]);

        $this->crud->addField([
               'name' => 'disponibility_date',
               'type' => 'date_picker',
               'label' => 'Date de disponibilité',
               // optional:
               'date_picker_options' => [
                  'todayBtn' => true,
                  'format' => 'dd-mm-yyyy',
                  'language' => 'fr'
               ],
        ]);

        $this->crud->addField([
            'name' => 'tjm',
            'label' => "TJM",
            'type' => 'number'
        ]);

        $this->crud->addField([
            'name' => 'profession',
            'label' => "Profession"
        ]);

        $this->crud->addField([
            'name' => 'year_start_experience',
            'label' => "Année de premiére experience premiére expérience",
            'type' => 'number'
        ]);

        $this->crud->addField([ 
            'name' => 'secteur_id',
            'label' => 'Secteur d\'activité',
            'type' => "select2",
            'entity' => 'secteur', 
            'attribute' => "title", 
            'model' => "App\Models\Secteur",
        ]);

        $this->crud->addField([ 
            'label' => "Curriculum vitae",
            'name' => "curriculum_vitae",
            'type' => 'upload',
            'upload' => true,
        ]);

        $this->crud->addField([       // Select2Multiple = n-n relationship (with pivot table)
            'label' => "Technologies",
            'type' => 'select2_multiple',
            'name' => 'technology', // the method that defines the relationship in your Model
            'entity' => 'technology', // the method that defines the relationship in your Model
            'attribute' => 'title', // foreign key attribute that is shown to user
            'model' => "App\Models\Technology", // foreign key model
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
        ]);

        $this->crud->addField([
            'label' => "Compétence",
            'type' => 'select2_multiple',
            'name' => 'skill', 
            'entity' => 'skill',
            'attribute' => 'title',
            'model' => "App\Models\Skill",
            'pivot' => true, 
        ]);

        $this->crud->addField([ 
            'name' => 'status',
            'label' => 'Status',
            'type' => 'select2_from_array',
            'options' => ['aprouved' => 'Aprouvé', 'current' => 'En cours', 'banned' => 'Banné'],
            'allows_null' => false,
        ]);

        $this->crud->addField([   // Hidden
            'name' => 'password',
            'type' => 'hidden',
            'value' => rand(1111111, 999999999)
        ]);

        $this->crud->addField([
            'name' => 'diplomes',
            'label' => 'Listes des diplômes',
            'type' => 'diplomes',
            'listes_diplomes' => $this->diplomes(),
            'user_diplomes' => $this->user_diplomes()
        ]);

        $this->crud->addField([
            'name' => 'experiences',
            'label' => 'Listes des expériences',
            'type' => 'experiences',
            'user_experiences' => $this->user_experiences(),
            'competances' => $this->competances(),
            'technologies' => $this->technologies()
        ]);

        $this->crud->addField([
            'name' => 'certificates',
            'label' => 'Listes des certifications',
            'type' => 'certificates',
            'user_certifs' => $this->user_certifs()
        ]);

        $this->crud->addField([
            'name' => 'scripts',
            'type' => 'scripts'
        ]);

    }

    public function technologies() {
        return Technology::get(['id', 'title']);
    }

    public function competances() {
        return Skill::get(['id', 'title']);
    }
    
    public function user_certifs() {
        $consultent_id = \Route::current()->parameter('consultent');
        if(!empty($consultent_id))
            return Certification::where('user_id', '=', $consultent_id)->get(['id', 'title', 'certif']);
        else
            return [];
    }

    public function user_diplomes() {
        $consultent_id = \Route::current()->parameter('consultent');
        if(!empty($consultent_id))
            return UserDiploma::where('user_id', '=', $consultent_id)->get(['id', 'year', 'school', 'speciality', 'diploma_id']);
        else
            return [];
    }

    public function user_experiences() {
        $consultent_id = \Route::current()->parameter('consultent');
        if(!empty($consultent_id))
            return Experience::with(['skill', 'technology'])->where('user_id', '=', $consultent_id)->get(['id', 'title', 'description', 'date_start', 'date_end', 'client', 'tjm']);
        else
            return [];
    }

    public function diplomes()  {
        return Diploma::get(['id', 'title']);
    }

	public function store(StoreRequest $request)
	{
        $certif_title = $this->request->request->get('certifs')["title"];
        if(!empty($request['certifs']['certif']))
            $certif_certif = $request['certifs']['certif'];
        $deletedcertif = json_decode($this->request->request->get('certifs')["deletedcertifs"]);
        $certif_ids = $this->request->request->get('certifs')["id"];

        $experience_titles = $this->request->request->get('experiences')['title'];
        $experience_descriptions = $this->request->request->get('experiences')['description'];
        $experience_tjms = $this->request->request->get('experiences')['tjm'];
        $experience_date_starts = $this->request->request->get('experiences')['date_start'];
        $experience_date_ends = $this->request->request->get('experiences')['date_end'];
        $experience_clients = $this->request->request->get('experiences')['client'];
        $experience_ids = $this->request->request->get('experiences')['id'];
        $deletedexperience = json_decode($this->request->request->get('experiences')['deletedexperience']);
        $experience_technologies = $this->request->request->get('experiences')['technologies'];
        $experience_competances = $this->request->request->get('experiences')['competances'];

        if(!empty($this->request->request->get('diplomes')["speciality"]))
            $specialities = $this->request->request->get('diplomes')["speciality"];

        if(!empty($this->request->request->get('diplomes')["year"]))
            $years = $this->request->request->get('diplomes')["year"];

        if(!empty($this->request->request->get('diplomes')["diploma_id"]))
            $diplomas = $this->request->request->get('diplomes')["diploma_id"];

        if(!empty($this->request->request->get('diplomes')["school"]))
            $schools = $this->request->request->get('diplomes')["school"];

        if(!empty($this->request->request->get('diplomes')["id"]))        
            $ids = $this->request->request->get('diplomes')["id"];
        
        $email = $this->request->request->get('email');
        $item_stored = parent::storeCrud();
        $user_id = User::where('email', '=', $email)->value('id');



        for ($i=0; $i < count($ids); $i++) { 
            if(!empty($diplomas[$i]) && !empty($years[$i]) && !empty($specialities[$i]) && !empty($schools[$i])) {
                if($ids[$i] == 'new') {
                    $user_diploma = new UserDiploma;
                    $user_diploma->speciality = $specialities[$i];
                    $user_diploma->diploma_id = $diplomas[$i];
                    $user_diploma->year = $years[$i];
                    $user_diploma->school = $schools[$i];
                    $user_diploma->user_id = $user_id;
                    $user_diploma->save();
                }
            }
        }

        for ($i=0; $i < count($experience_ids); $i++) {
            if(!empty($experience_titles[$i])) {
                if($experience_ids[$i] == 'new') {
                    $experience = new Experience;
                    $experience->title = $experience_titles[$i];
                    $experience->description = $experience_descriptions[$i];
                    $experience->date_start = $experience_date_starts[$i];
                    $experience->date_end = $experience_date_ends[$i];
                    $experience->user_id = $user_id;
                    $experience->client = $experience_clients[$i];
                    $experience->tjm = $experience_tjms[$i];
                    $experience->save();
                }
                if(!empty($experience_technologies[$i])){
                    for ($j=0; $j < count($experience_technologies[$i]); $j++) {
                        if($experience_technologies[$i][$j] != '*')
                           DB::insert('insert into experience_technologies (experience_id, technology_id) values (?, ?)', [$experience->id, $experience_technologies[$i][$j]]);
                    }
                }

                if(!empty($experience_competances[$i])){
                    for ($j=0; $j < count($experience_competances[$i]); $j++) {
                        if($experience_competances[$i][$j] != '*')
                            DB::insert('insert into experience_skills (experience_id, skill_id) values (?, ?)', [$experience->id, $experience_competances[$i][$j]]);
                    }
                }
            }
        }

        for ($i=0; $i < count($certif_ids); $i++) {
            if(!empty($certif_title[$i])) {
                if(!empty($certif_certif[$i])) {
                    $fileName = explode('.', $certif_certif[$i]->getClientOriginalName());
                    $fileName = 'uploads/certifs/'.md5(uniqid(rand(), true)).'.'.$fileName[1];
                    Storage::disk('uploads')->put($fileName, file_get_contents($certif_certif[$i]));
                }
                
                if($certif_ids[$i] == 'new') {
                    $certif = new Certification;
                    $certif->title = $certif_title[$i];
                    $certif->user_id = $user_id;
                    if(!empty($fileName))
                        $certif->certif = $fileName;
                    $certif->save();
                }
            }
        }

        $user = User::find($user_id);//where('id', '=', $user_id)->first();
        $user->admin_id = Auth::user()->name;
        if(!empty($user->curriculum_vitae)) {
            $content = $this->extractcvtext($user->curriculum_vitae);
            $user->cv_content = $content;
            $user->save();
        } else {
        	$user->save();
        }
        return redirect('admin/consultent');
	}

	public function update(UpdateRequest $request)
	{
        $deletediplomas = json_decode($this->request->request->get('diplomes')["deletediplomas"]);
        $specialities = $this->request->request->get('diplomes')["speciality"];
        $years = $this->request->request->get('diplomes')["year"];
        if(isset($this->request->request->get('diplomes')["diploma_id"]))
            $diplomas = $this->request->request->get('diplomes')["diploma_id"];
        $schools = $this->request->request->get('diplomes')["school"];
        $ids = $this->request->request->get('diplomes')["id"];
        $user_id = $this->request->request->get('id');

        $certif_title = $this->request->request->get('certifs')["title"];
        if(!empty($request['certifs']['certif']))
            $certif_certif = $request['certifs']['certif'];
        $deletedcertif = json_decode($this->request->request->get('certifs')["deletedcertifs"]);
        $certif_ids = $this->request->request->get('certifs')["id"];

        $experience_titles = $this->request->request->get('experiences')['title'];
        $experience_descriptions = $this->request->request->get('experiences')['description'];
        $experience_tjms = $this->request->request->get('experiences')['tjm'];
        $experience_date_starts = $this->request->request->get('experiences')['date_start'];
        $experience_date_ends = $this->request->request->get('experiences')['date_end'];
        $experience_clients = $this->request->request->get('experiences')['client'];
        $experience_ids = $this->request->request->get('experiences')['id'];
        $deletedexperience = json_decode($this->request->request->get('experiences')['deletedexperience']);
        $experience_technologies = $this->request->request->get('experiences')['technologies'];
        $experience_competances = $this->request->request->get('experiences')['competances'];
        
		parent::updateCrud();

        if(!empty($deletediplomas))
            UserDiploma::whereIn('id', $deletediplomas)->delete();

        if(!empty($deletedcertif))
            Certification::whereIn('id', $deletedcertif)->delete();

        if(!empty($deletedexperience))
            Experience::whereIn('id', $deletedexperience)->delete();
        
        for ($i=0; $i < count($experience_ids); $i++) {
            if(!empty($experience_titles[$i])) {
                if($experience_ids[$i] == 'new') {
                    $experience = new Experience;
                    $experience->title = $experience_titles[$i];
                    $experience->description = $experience_descriptions[$i];
                    $experience->date_start = $experience_date_starts[$i];
                    $experience->date_end = $experience_date_ends[$i];
                    $experience->user_id = $user_id;
                    $experience->client = $experience_clients[$i];
                    $experience->tjm = $experience_tjms[$i];
                    $experience->save();
                } else {
                    $experience = Experience::find($experience_ids[$i]);
                    $experience->title = $experience_titles[$i];
                    $experience->description = $experience_descriptions[$i];
                    $experience->date_start = $experience_date_starts[$i];
                    $experience->date_end = $experience_date_ends[$i];
                    $experience->user_id = $user_id;
                    $experience->client = $experience_clients[$i];
                    $experience->tjm = $experience_tjms[$i];
                    $experience->save();
                }
                $deleted = DB::delete('delete from experience_technologies where experience_id ='.$experience->id);
                if(!empty($experience_technologies[$i])){
                    for ($j=0; $j < count($experience_technologies[$i]); $j++) { 
                        if($experience_technologies[$i][$j] != '*')
                            DB::insert('insert into experience_technologies (experience_id, technology_id) values (?, ?)', [$experience->id, $experience_technologies[$i][$j]]);
                    }
                }

                $deleted = DB::delete('delete from experience_skills where experience_id ='.$experience->id);
                if(!empty($experience_competances[$i])){
                    for ($j=0; $j < count($experience_competances[$i]); $j++) { 
                        if($experience_competances[$i][$j] != '*')
                            DB::insert('insert into experience_skills (experience_id, skill_id) values (?, ?)', [$experience->id, $experience_competances[$i][$j]]);
                    }
                }
            }
        }

        for ($i=0; $i < count($certif_ids); $i++) {
            if(!empty($certif_title[$i])) {
                if(!empty($certif_certif[$i])) {
                    $fileName = explode('.', $certif_certif[$i]->getClientOriginalName());
                    $fileName = 'uploads/certifs/'.md5(uniqid(rand(), true)).'.'.$fileName[1];
                    Storage::disk('uploads')->put($fileName, file_get_contents($certif_certif[$i]));
                }
                
                if($certif_ids[$i] == 'new') {
                    $certif = new Certification;
                    $certif->title = $certif_title[$i];
                    $certif->user_id = $user_id;
                    if(!empty($fileName))
                        $certif->certif = $fileName;
                    $certif->save();
                } else {
                    $certif = Certification::find($certif_ids[$i]);
                    $certif->title = $certif_title[$i];
                    $certif->user_id = $user_id;
                    if(!empty($fileName))
                        $certif->certif = $fileName;
                    $certif->save();
                }
            }
        }

        for ($i=0; $i < count($ids); $i++) { 
            if(!empty($diplomas[$i]) && !empty($years[$i]) && !empty($specialities[$i]) && !empty($schools[$i])) {
                if($ids[$i] == 'new') {
                    $user_diploma = new UserDiploma;
                    $user_diploma->speciality = $specialities[$i];
                    $user_diploma->diploma_id = $diplomas[$i];
                    $user_diploma->year = $years[$i];
                    $user_diploma->school = $schools[$i];
                    $user_diploma->user_id = $user_id;
                    $user_diploma->save();
                } else {
                    $user_diploma = UserDiploma::find($ids[$i]);
                    // dd($schools[$i]);
                    if(!empty($user_diploma)) {
                        $user_diploma->speciality = $specialities[$i];
                        $user_diploma->diploma_id = $diplomas[$i];
                        $user_diploma->year = $years[$i];
                        $user_diploma->school = $schools[$i];
                        $user_diploma->save();
                    }
                }
            }
        }

        $user = User::find($this->request->request->get('id'));//where('id', '=', $this->request->request->get('id'))->first();
        if(!empty($user->curriculum_vitae)) {
            $content = $this->extractcvtext($user->curriculum_vitae);
            $user->cv_content = $content;
            $user->save();
        } else {
			$user->save();
        }
        return redirect('admin/consultent');
	}

    public function extractcvtext($filename) {
        if(env('APP_ENVIRONEMENT') != 'host')
            $filename = str_replace('/', '\\', $filename);
        $path = public_path($filename);
        $file_parts = pathinfo($path);
        if($file_parts['extension'] == 'doc') {
            $content = $this->getRawWordText($path);
        } elseif($file_parts['extension'] == 'docx') {
            $content = $this->read_file_docx($path);
        }
        $content = str_replace(array("\r", "\n", "\t", "\v"), " ", $content);
        return $content;
    }


    function read_file_docx($filename){
        $striped_content = '';
        $content = '';
        if(!$filename || !file_exists($filename)) return false;
        $zip = zip_open($filename);
        if (!$zip || is_numeric($zip)) return false;
        while ($zip_entry = zip_read($zip)) {
            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
            if (zip_entry_name($zip_entry) != "word/document.xml") continue;
            $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
            zip_entry_close($zip_entry);
        }
        zip_close($zip);
        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
        $content = str_replace('</w:r></w:p>', " ", $content);
        $striped_content = strip_tags($content);
        return $striped_content;
    }


    function getRawWordText($filename) {
        // dd($filename);
      if(file_exists($filename)) {
        if(($fh = fopen($filename, 'r')) !== false ) {
            $headers = fread($fh, 0xA00);
            $n1 = ( ord($headers[0x21C]) - 1 );
            $n2 = ( ( ord($headers[0x21D]) - 8 ) * 256 );
            $n3 = ( ( ord($headers[0x21E]) * 256 ) * 256 );
            $n4 = ( ( ( ord($headers[0x21F]) * 256 ) * 256 ) * 256 );
            $textLength = ($n1 + $n2 + $n3 + $n4);
            $extracted_plaintext = fread($fh, $textLength);
            fclose($fh);
            $extracted_plaintext = mb_convert_encoding($extracted_plaintext, "ISO-8859-1", mb_detect_encoding($extracted_plaintext, "UTF-8, ISO-8859-1, ISO-8859-15", true));
            return utf8_encode($extracted_plaintext);
        } else {
            return false;
        }
      } else {
        return false;
      }  
    }
}