<?php

namespace App;

use App\Tips\Tip;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Cohort
 * @property integer $id
 * @property string $name
 * @property Tip[]|Collection $tips
 * @property Category[]|Collection $categories
 * @property EducationProgram $educationProgram
 * @property boolean $disabled
 *
 */
class Cohort extends Model
{

    protected $fillable = ["name", "description", "ep_id"];
    public $timestamps = false;


    /**
     * @return HasMany
     */
    public function categories()
    {
        return tap($this->hasMany(Category::class, 'cohort_id', 'id'))->where('wplp_id', '0');
    }

    public function competencies()
    {
        return $this->hasMany(Competence::class, 'cohort_id', 'id');
    }

    public function competenceDescription()
    {
        return $this->hasOne(CompetenceDescription::class, 'cohort_id', 'id');
    }

    public function educationProgram()
    {
        return $this->belongsTo(EducationProgram::class, "ep_id", "ep_id");
    }

    public function resourcePersons()
    {
        // Tap so we can apply the where clause but still return the relationship
        return tap($this->hasMany(ResourcePerson::class, 'cohort_id', 'id'))->where('wplp_id', '0');
    }

    public function timeslots()
    {
        return $this->hasMany(Timeslot::class, 'cohort_id', 'id');
    }

    public function workplaceLearningPeriods()
    {
        return $this->hasMany(WorkplaceLearningPeriod::class, 'cohort_id', 'id');
    }

    public function tips() {
        return $this->belongsToMany(Tip::class);
    }

    // Relations for query builder
    public function getRelationships()
    {
        return ["categories", "competencies", "competenceDescription", "educationProgram", "resourcePersons", "timeSlots", "workplaceLearningPeriods"];
    }

}
