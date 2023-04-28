<?php

declare(strict_types=1);

/**
 * @copyright 2023, Aleix Quintana Alsius <kinta@communia.org>
 *
 * @author Aleix Quintana Alsius <kinta@communia.org>
 *
 * @license AGPL-3.0-or-later
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\CorsOriginFilterSettings\Controller;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IConfig;
use OCP\IRequest;

class OriginsFilterController extends Controller {

	/** @var IConfig */
	private $config;

	/**
	 * OriginsFilterController constructor.
	 *
	 * @param string $appName
	 * @param IRequest $request
	 * @param IConfig $config
	 */
	public function __construct(string $appName,
								IRequest $request,
								IConfig $config) {
		parent::__construct($appName, $request);

		$this->config = $config;
	}

	/**
	 * @return JSONResponse
	 */
	public function getGeneralAll(): JSONResponse {
		$general_origins = ['origin' => $this->config->getAppValue('corsOriginFilterSettings', 'allowed_origins', "")];
		return new JSONResponse($general_origins);
	}

	/**
	 * @param string $origin
	 * @return JSONResponse
	 */
	public function addGeneral(string $origin): JSONResponse {
		$origins = explode(",", $origin);
		$origins = array_map('trim', $origins);
		foreach ($origins as $checked_origin) {
			if (!filter_var($checked_origin, FILTER_VALIDATE_URL)) {
				return new JSONResponse([""], Http::STATUS_BAD_REQUEST);
			}
		}
		$value = implode(",", $origins);
		$this->config->setAppValue('corsOriginFilterSettings', 'allowed_origins', $value);
		return new JSONResponse([
			'origin' => $value,
		]);
	}

	/**
	 * @return JSONResponse
	 */
	public function removeGeneral(): JSONResponse {
		$this->config->deleteAppValue('corsOriginFilterSettings', 'allowed_origins');

		return new JSONResponse([]);
	}
	/**
	 * @return JSONResponse
	 */
	public function getPerAppAll(): JSONResponse {
		$keys = $this->config->getAppKeys('corsOriginFilterSettings');
		$keys = array_filter($keys, function ($key) {
			$regex = '/^(\S*)\.allowed_origins/S';
			return preg_match($regex, $key) === 1;
		});

		$result = [];

		foreach ($keys as $key) {
			$value = $this->config->getAppValue('corsOriginFilterSettings', $key);
			$result[] = [
				'app' => substr($key, 0, -16),
				'origin' => $value,
			];
		}

		return new JSONResponse($result);
	}

	/**
	 * @param string $origin
	 * @param string $app
	 * @return JSONResponse
	 */
	public function addPerApp(string $origin, string $app): JSONResponse {
		$origins = explode(",", $origin);
		$origins = array_map('trim', $origins);
		foreach ($origins as $checked_origin) {
			if (!filter_var($checked_origin, FILTER_VALIDATE_URL)) {
				return new JSONResponse(['message' => 'Bad origin syntax'], Http::STATUS_BAD_REQUEST);
			}
		}
		if ($this->config->getAppValue('corsOriginFilterSettings', $app. ".allowed_origins")) {
			return new JSONResponse(['message' => 'App exists, edit it instead.'], Http::STATUS_BAD_REQUEST);
		}

		$value = implode(",", $origins);
		$this->config->setAppValue('corsOriginFilterSettings', $app . '.allowed_origins', $value);
		return new JSONResponse([
			'origin' => $value,
			'app' => $app,
		]);
	}

	/**
	 * @param string $app
	 * @return JSONResponse
	 */
	public function removePerApp($app): JSONResponse {
		$this->config->deleteAppValue('corsOriginFilterSettings', $app . '.allowed_origins');

		return new JSONResponse([]);
	}
}
