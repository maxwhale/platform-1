<?php

/**
 * Ushahidi Tag Authorizer
 *
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi\Application
 * @copyright  2014 Ushahidi
 * @license    https://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License Version 3 (AGPL3)
 */

namespace Ushahidi\Tool\Authorizer;

use Ushahidi\Entity;
use Ushahidi\Entity\User;
use Ushahidi\Entity\Tag;
use Ushahidi\Tool\Authorizer;
use Ushahidi\Traits\EnsureUserEntity;
use Ushahidi\Traits\AdminAccess;
use Ushahidi\Traits\UserContext;

// The `TagAuthorizer` class is responsible for access checks on `Tags`
class TagAuthorizer implements Authorizer
{
	// The access checks are run under the context of a specific user
	use UserContext;

	// - `AdminAccess` to check if the user has admin access
	use AdminAccess;

	protected function isUserOfRole(Tag $entity, $user)
	{
		$roles = $entity->getRoleArray();

		if ($roles) {
			return in_array($user->role, $roles);
		}

		// If no roles are selected, the Tag is considered completely public.
		return true;
	}

	/* Authorizer */
	public function isAllowed(Entity $entity, $privilege)
	{
		// These checks are run within the user context.
		$user = $this->getUser();

		// Then we check if a user has the 'admin' role. If they do they're
		// allowed access to everything (all entities and all privileges)
		if ($this->isUserAdmin($user)) {
			return true;
		}

		// Finally, we check if the Tag is only visible to specific roles.
		if ($this->isUserOfRole($entity, $user)) {
			return true;
		}

		// If no other access checks succeed, we default to denying access
		return false;
	}
}