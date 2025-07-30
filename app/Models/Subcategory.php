<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 
 *
 * @property int $id
 * @property int $category_id
 * @property string $nome
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\File> $files
 * @property-read int|null $files_count
 * @method static \Database\Factories\SubcategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subcategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subcategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subcategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subcategory whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subcategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subcategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subcategory whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subcategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'category_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }
}
