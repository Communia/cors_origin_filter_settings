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
	<NcTextField id="generalAllowedOriginInput"
		:value.sync="localOrigins"
		:label="t('cors_origin_filter_settings','General allowed origins')"
		:label-visible="true"
		:placeholder="t('cors_origin_filter_settings','https://cms.example.com,https://example.com')"
		type="text"
		:spellcheck="false"
		:success="showSuccess"
		:error="Boolean(errorMessage)"
		:helper-text="errorMessage||successMessage"
		:show-trailing-button="localOrigins !== defaultValue"
		trailing-button-icon="undo"
		@trailing-button-click="undo"
		@keydown.enter="addOrigins"
		@blur="addOrigins" />
</template>

<script>
import { generateUrl } from '@nextcloud/router'
import axios from '@nextcloud/axios'
import { NcTextField } from '@nextcloud/vue'

export default {
	name: 'GeneralAllowedOriginField',
	components: {
		NcTextField,
	},
	props: {
		origins: {
			type: String,
			default: '',
		},
	},
	data() {
		return {
			localOrigins: this.origins,
			showSuccess: false,
			successMessage: '',
			errorMessage: '',
			defaultValue: '',
		}
	},
	beforeMount() {
		axios.get(generateUrl('apps/cors_origin_filter_settings/allowed_origins/general'))
			.then((response) => {
				this.localOrigins = response.data.origin
				this.defaultValue = this.localOrigins
			})
			.catch((error) => {
				this.errorMessage = error.message
				this.handleSuccess()
			})

	},
	methods: {
		reset() {
			this.showSuccess = false
			this.successMessage = ''
			this.errorMessage = ''
		},
		handleSuccess() {
			this.showSuccess = true
			setTimeout(() => {
				this.showSuccess = false
				this.successMessage = ''
			}, 2000)
		},
		addOrigins() {
			this.reset()
			axios.post(generateUrl('apps/cors_origin_filter_settings/allowed_origins/general'),
				{
					origin: this.localOrigins,
				})
				.then((response) => {
					this.successMessage = t('cors_origin_filter_settings', 'Origins saved')
					this.defaultValue = this.localOrigins
					this.handleSuccess()
				})
				.catch((error) => {
					this.errorMessage = (error.code === 'ERR_BAD_REQUEST') ? t('cors_origin_filter_settings', 'Bad syntax url, not saved') : error.message
					this.handleSuccess()
				})
		},
		undo() {
			this.reset()
			axios.get(generateUrl('apps/cors_origin_filter_settings/allowed_origins/general'))
				.then((response) => {
					this.localOrigins = response.data.origin
					this.successMessage = t('cors_origin_filter_settings', 'Recovered last saved origins')
					this.handleSuccess()
				})
				.catch((error) => {
					this.errorMessage = error.message
					this.handleSuccess()
				})
		},
	},
}
</script>
