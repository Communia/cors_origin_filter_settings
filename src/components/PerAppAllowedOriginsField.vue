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
	<div>
		<table id="per-app-allowed-origins-list">
			<tbody>
				<PerAppAllowedOriginItem v-for="item in perAppItems"
					:key="item.app"
					:item="item"
					@delete="deletePerAppAllowedOrigin"
					@update="updatePerAppAllowedOrigin" />
			</tbody>
		</table>

		<h3>{{ t('cors_origin_filter_settings', 'Add new per app allowed origin') }}</h3>
		<form @submit.prevent="addPerAppAllowedOrigin">
			<input id="origin"
				v-model="newPerAppAllowed.origin"
				type="text"
				name="origin"
				placeholder="https://example.com"> in app
			<input id="app"
				v-model="newPerAppAllowed.app"
				type="text"
				name="mask"
				placeholder="files_sharing">

			<NcButton native-type="submit">
				<template #icon>
					<Plus />
				</template>
				{{ t('cors_origin_filter_settings', 'Add') }}
			</NcButton>
		</form>
		<span v-if="showSuccess"
			id="per-app-allowed-origins-errors">
			{{ errorMessage }}
		</span>
	</div>
</template>

<script>
import { generateUrl } from '@nextcloud/router'
import axios from '@nextcloud/axios'
import { NcButton } from '@nextcloud/vue'
import Plus from 'vue-material-design-icons/Plus'
import PerAppAllowedOriginItem from './PerAppAllowedOriginItem.vue'

export default {
	name: 'PerAppAllowedOriginsField',
	components: {
		NcButton,
		PerAppAllowedOriginItem,
		Plus,
	},
	props: {
	},
	data() {
		return {
			perAppItems: [],
			newPerAppAllowed: {
				origin: '',
				app: '',
			},
			errorMessage: '',
			showSuccess: false,
		}
	},
	beforeMount() {
		axios.get(generateUrl('apps/cors_origin_filter_settings/allowed_origins/per_app'))
			.then((response) => {
				this.perAppItems = response.data
			})
	},
	methods: {
		deletePerAppAllowedOrigin(app) {
			axios.delete(generateUrl('apps/cors_origin_filter_settings/allowed_origins/per_app/{app}', { app }))
				.then((response) => {
					this.perAppItems = this.perAppItems.filter(item => item.app !== app)
				})
		},
		addPerAppAllowedOrigin() {
			axios.post(generateUrl('apps/cors_origin_filter_settings/allowed_origins/per_app'),
				{
					origin: this.newPerAppAllowed.origin,
					app: this.newPerAppAllowed.app,
				})
				.then((response) => {
					this.perAppItems.push(response.data)
					this.newPerAppAllowed.origin = ''
					this.newPerAppAllowed.app = ''
				}).catch((error) => {
					this.errorMessage = error.response.data.message
					this.handleSuccess()
				})
		},
		updatePerAppAllowedOrigin(app, origin) {
			axios.delete(generateUrl('apps/cors_origin_filter_settings/allowed_origins/per_app/{app}', { app }))
				.then((response) => {
					this.newPerAppAllowed.origin = origin
					this.newPerAppAllowed.app = app
					this.perAppItems = this.perAppItems.filter(item => item.app !== app)
				})
		},
		handleSuccess() {
			this.showSuccess = true
			setTimeout(() => {
				this.showSuccess = false
				this.errorMessage = ''
			}, 2000)
		},
	},
}
</script>

<style lang="scss" scoped>
#per-app-allowed-origins-list {
	min-width: 262px;
}

#per-app-allowed-origins-errors{
	color: var(--color-error);
}

form {
	display: flex;
	align-items: center;
	input {
		margin: 8px;
		&#origin {
			width:400px;
		}
	}
}
</style>
