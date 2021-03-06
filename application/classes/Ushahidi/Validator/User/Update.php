<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Ushahidi User Update Validator
 *
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi\Application
 * @copyright  2014 Ushahidi
 * @license    https://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License Version 3 (AGPL3)
 */

use Ushahidi\Core\Entity;
use Ushahidi\Core\Tool\Validator;
use Ushahidi\Core\Entity\UserRepository;
use Ushahidi\Core\Entity\User;
use Ushahidi\Core\Usecase\User\UpdateUserData;
use Ushahidi\Core\Entity\RoleRepository;
use Ushahidi\Core\Traits\UserContext;

class Ushahidi_Validator_User_Update extends Validator
{
	use UserContext;

	protected $repo;
	protected $role_repo;
	protected $valid;

	public function __construct(UserRepository $repo, RoleRepository $role_repo)
	{
		$this->repo = $repo;
		$this->role_repo = $role_repo;
	}

	protected function getRules()
	{
		return [
			'email' => [
				['email'],
				['max_length', [':value', 150]],
				[[$this->repo, 'isUniqueEmail'], [':value']],
			],
			'realname' => [
				['max_length', [':value', 150]],
			],
			'username' => [
				['max_length', [':value', 50]],
				['regex', [':value', '/^[a-z][a-z0-9._-]+[a-z0-9]$/i']],
				[[$this->repo, 'isUniqueUsername'], [':value']],
			],
			'role' => [
				[[$this->role_repo, 'exists'], [':value']],
			],
			'password' => [
				['min_length', [':value', 7]],
				['max_length', [':value', 72]],
			],
		];
	}
}
