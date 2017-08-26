<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;
use Koodilab\Contracts\Models\Behaviors\Timeable as TimeableContract;
use Koodilab\Events\UserUpdated;
use Koodilab\Models\Behaviors\Timeable;
use Koodilab\Models\Relations\BelongsToUser;

/**
 * Research.
 *
 * @property int $id
 * @property int $user_id
 * @property int $researchable_id
 * @property string $researchable_type
 * @property \Carbon\Carbon $ended_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read int $remaining
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $researchable
 * @property-read User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereResearchableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereResearchableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereUserId($value)
 * @mixin \Eloquent
 */
class Research extends Model implements TimeableContract
{
    use Timeable, BelongsToUser;

    /**
     * {@inheritdoc}
     */
    protected $perPage = 30;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'ended_at',
    ];

    /**
     * Get the researchable.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function researchable()
    {
        return $this->morphTo();
    }

    /**
     * {@inheritdoc}
     */
    public function finish()
    {
        switch ($this->researchable_type) {
            case Resource::class:
                $this->user->resources()->attach($this->researchable_id);
                break;
            case Unit::class:
                $this->user->units()->attach($this->researchable_id);
                break;
        }

        $this->user->experience += $this->researchable->research_experience;
        $this->user->save();

        $this->delete();

        event(new UserUpdated($this->user->id));

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function cancel()
    {
    }
}