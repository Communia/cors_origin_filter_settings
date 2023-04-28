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
	<tr>
		<td><span><code>{{ origin }}</code> allowed for <code>{{ app }}</code> operations</span></td>
		<td class="action-column">
			<span>
				<a class="icon-delete has-tooltip" :title="t('cors_origin_filter_settings', 'Delete')" @click="$emit('delete', app)" />
			</span>
			<span>
				<a class="icon-edit has-tooltip" :title="t('cors_origin_filter_settings', 'Update')" @click="$emit('update', app, origin)" />
			</span>
		</td>
	</tr>
</template>

<script>
export default {
	name: 'PerAppAllowedOriginItem',
	props: {
		item: {
			type: Object,
			required: true,
		},
	},
	data() {
		return {
			origin: this.item.origin,
			app: this.item.app,
		}
	},
}
</script>

<style scoped>
	td span {
		padding: 10px 15px;
		display: inline-block;
	}

	.action-column {
		width: 46px;
	}

	.action-column a {
		display: inline-block;
	}
</style>
