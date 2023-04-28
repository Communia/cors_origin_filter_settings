<!--
  - @copyright Copyright (c) 2023 Aleix Quintana Alsius <kinta@communia.org>
  -
  - @author Aleix Quintana Alsius <kinta@communia.org>
  -
  - @license AGPL-3.0-or-later
  -
  - This program is free software: you can redistribute it and/or modify
  - it under the terms of the GNU Affero General Public License as
  - published by the Free Software Foundation, either version 3 of the
  - License, or (at your option) any later version.
  -
  - This program is distributed in the hope that it will be useful,
  - but WITHOUT ANY WARRANTY; without even the implied warranty of
  - MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  - GNU Affero General Public License for more details.
  -
  - You should have received a copy of the GNU Affero General Public License
  - along with this program. If not, see <http://www.gnu.org/licenses/>.
  -
  -->
<template>
	<NcSettingsSection :title="t('cors_origin_filter_settings', 'CORS Allowed Origins')"
		doc-url="https://docs.nextcloud.com/server/stable/admin_manual/configuration_server/cors_filter_configuration.html">
		<p class="settings-hint">
			{{ t('cors_origin_filter_settings', 'To allow origins to send requests in apps url endpoints specify them below. Note that any allowed origin can perform requests, often means using all the CRUD methods, in browser scope. For security reasons, it is recommended to allow as few origins as possible.') }}
		</p>
		<GeneralAllowedOriginField origins="" />
		<h3>{{ t('cors_origin_filter_settings', 'Per-app allowed origins') }}</h3>
		<PerAppAllowedOriginsField />
	</NcSettingsSection>
</template>

<script>
import { NcSettingsSection } from '@nextcloud/vue'
import GeneralAllowedOriginField from './components/GeneralAllowedOriginField.vue'
import PerAppAllowedOriginsField from './components/PerAppAllowedOriginsField.vue'

export default {
	name: 'App',
	components: {
		GeneralAllowedOriginField,
		PerAppAllowedOriginsField,
		NcSettingsSection,
	},
}
</script>
