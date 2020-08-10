<template>
<div>
	<input type="file" name="deposit_slip" id="c_image" ref="image" class="mb-3" @change="onDepositSlipChange" required>
	<img :src="avatar" class="img-fluid img-thumbnail">
</div>
</template>

<script>
	export default {
		data() {
			return {
				avatar: '/images/default-thumbnail.jpg',
				image: ''
			}
		},
		methods: {
			onDepositSlipChange(e) {
				let files = e.target.files || e.dataTransfer.files
				if (!files.length)
					return;
				// this.creteImage(files[0])
				this.image = files[0]
				this.createImage(this.image)
			},
			createImage(file) {
				let reader = new FileReader()
	
				let vm = this
				reader.onload = (e)	=> {
					vm.avatar = e.target.result
				}
				reader.readAsDataURL(file)
			}
		}
	}
</script>
