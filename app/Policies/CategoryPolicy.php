<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admin e company_admin podem listar categorias se tiverem uma empresa
        return in_array($user->role, ['admin', 'company_admin']) && $user->empresa_id !== null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Category $category): bool
    {
        // Admin e company_admin podem ver a categoria se pertencer à sua empresa
        // Técnico normal pode ver se a categoria for a dele
        if (in_array($user->role, ['admin', 'company_admin'])) {
            return $user->empresa_id === $category->empresa_id;
        }
        
        if ($user->role === 'normal_technician') {
            return $user->category_id === $category->id && $user->empresa_id === $category->empresa_id;
        }
        
        if ($user->role === 'general_technician') {
            return $user->empresa_id === $category->empresa_id;
        }
        
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Admin e company_admin podem criar categorias se tiverem uma empresa
        return in_array($user->role, ['admin', 'company_admin']) && $user->empresa_id !== null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Category $category): bool
    {
        // Admin e company_admin podem atualizar a categoria se pertencer à sua empresa
        return in_array($user->role, ['admin', 'company_admin']) && $user->empresa_id === $category->empresa_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Category $category): bool
    {
        // Admin e company_admin podem deletar a categoria se pertencer à sua empresa
        return in_array($user->role, ['admin', 'company_admin']) && $user->empresa_id === $category->empresa_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Category $category): bool
    {
        // Implementar se usar soft deletes e quiser permitir restauração
        return in_array($user->role, ['admin', 'company_admin']) && $user->empresa_id === $category->empresa_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Category $category): bool
    {
        // Implementar se usar soft deletes e quiser permitir restauração
        return in_array($user->role, ['admin', 'company_admin']) && $user->empresa_id === $category->empresa_id;
    }
}
