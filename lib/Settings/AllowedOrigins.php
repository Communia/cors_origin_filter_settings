<?php

declare(strict_types=1);

/**
 * @copyright 2023, Aleix Quintana Alsius <<kinta@communia.org>
 *
 * @author Aleix Quintana Alsius <kinta@communia.org>
 *
 * @license GNU AGPL version 3 or any later version
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

namespace OCA\CorsOriginFilterSettings\Settings;

use OCP\AppFramework\Http\TemplateResponse;
use OCP\Settings\ISettings;

class AllowedOrigins implements ISettings {
	public function getForm(): TemplateResponse {
		return new TemplateResponse('cors_origin_filter_settings', 'allowed_origins');
	}

	public function getSection(): string {
		return 'security';
	}

	public function getPriority(): int {
		return 50;
	}
}
