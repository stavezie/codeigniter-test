<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<title>Document</title>
</head>
<body>






<div class="container">
	<div class="w-100 h-100 mt-5">
		<div class="row">
			<div class="col-6">
					<div>
						<div class="input-group input-group-sm mb-3">
							<span class="input-group-text" id="inputGroup-sizing-sm">Название</span>
							<input type="text" class="form-control noteName" placeholder="Введите название записи" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
						</div>
						<select class="form-select catName" aria-label="Default select example">
							<option selected>Выберите категорию</option>
							<?php foreach ($categories as $item): ?>
								<option value="<?=$item['id']?>"><?=$item['catname']?></option>
							<?php endforeach; ?>
						</select>
						<div class="form-check mt-1">
							<input class="form-check-input is_bought"  type="checkbox" value="1" id="flexCheckDefault">
							<label class="form-check-label" for="flexCheckDefault">
								Куплено
							</label>
						</div>

						<p class="fs-3 addNote" style="cursor: pointer">
							Добавить запись.
						</p>
					</div>
			</div>
			<div class="col-6">
				<div class="input-group input-group-sm mb-3">
					<span class="input-group-text" id="inputGroup-sizing-sm">Название</span>
					<input type="text" class="form-control catName" placeholder="Введите название категории" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
				</div>
				<p href="#" class="fs-3 addCategory" style="cursor: pointer">
					Добавить Категорию.
				</p>
			</div>
		</div>
		<table class="table">
			<thead>
			<tr>
<!--				<th scope="col">#</th>-->
				<th scope="col">Name</th>
				<th scope="col">
					<select onchange="sorting()" class="form-select categories sorting" aria-label="Default select example">
						<option selected>Category</option>
						<?php foreach ($categories as $item): ?>
							<option value="<?=$item['id']?>"><?=$item['catname']?></option>
						<?php endforeach; ?>
					</select></th>
				<th scope="col">
					<select onchange="sorting()" class="form-select is_boughtSort sorting" aria-label="Default select example">
						<option selected>Is bought?</option>
						<option value="1">True</option>
						<option value="0">False</option>
					</select>
				</th>
				<th scope="col">Managment</th>
				<th scope="col">Created at</th>
			</tr>
			</thead>
			<tbody class="tb">

			<?php foreach ($records as $item): ?>
				<tr class="table__item" id="<?=$item['tmp_id']?>">
<!--					<th scope="row">--><?//=$item['record_id']?><!--</th>-->
					<td><?=$item['name']?></td>
					<td><?=$item['catname']?></td>
					<td>
						<span class="isBoughtChanger">
							<?= $item['is_bought'] ? 'True' : 'False' ?>
						</span>
					</td>
					<td>
						<span class="deleteRecord" id="<?=$item['tmp_id']?>">
							Delete
						</span>
					</td>
					<td><?=$item['created_at']?></td>
				</tr>
			<?php endforeach; ?>


			</tbody>
		</table>
	</div>
</div>

<div class="cats"></div>

<script
		src="https://code.jquery.com/jquery-3.6.0.min.js"
		integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
		crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script>
	let ibState = false;

	let records = <?php echo json_encode($records); ?>;
	let categories = <?php echo json_encode($categories); ?>;
	let sortedArray = [];

	let changeIsBought = (item) => {
		let state = item.innerText === 'True' ? 'False' : 'True';
		let element = item.closest('tr');
		let tmp_id = element.id;
		console.log(tmp_id)

		$('.isBoughtChanger', element).text(state)
		let newState = state === 'True' ? '1' : '0';

		console.log(newState);
		$.ajax({
			method: "POST",
			url: "/table/updateIsBought",
			data: { newState: newState, id: tmp_id}
		})

		records = changeField(records, tmp_id, newState);
		sorting()

		console.log(records)
	}

	function sorting (item) {
		let sortByBought = $('select.is_boughtSort option:selected').val();
		let sortByCatName = $('select.categories option:selected').text();
		let sortByCatVal = $('select.categories option:selected').val();
		sortedArray = records.filter(item => {
			if (sortByBought === 'Is bought?' && sortByCatName === 'Category') {
				return item;
			}
			else if (sortByBought !== 'Is bought?' && sortByCatName === 'Category') {
				return item.is_bought === sortByBought;
			}
			else if (sortByBought === 'Is bought?' && sortByCatName !== 'Category') {
				return item.catid === sortByCatVal;
			}
			else if (sortByBought !== 'Is bought?' && sortByCatName !== 'Category') {
				return item.catid === sortByCatVal && item.is_bought === sortByBought;
			}
		})

		$('tbody.tb').html('');
		sortedArray.forEach(item => {
			let example = `
					<tr class="table__item" id="${item['tmp_id']}">
						<td>${item['name']}</td>
						<td>${item['catname']}</td>
						<td>
							<span onclick="changeIsBought(this)" class="isBoughtChanger">
								${item['is_bought'] == true ? 'True' : 'False'}
							</span>
						</td>
						<td>
							<span class="deleteRecord" onclick="deleteRecord(this)"  id="${item['tmp_id']}">
								Delete
							</span>
						</td>
						<td>Refactor</td>
					</tr>
			`;

			$('tbody.tb').append(example);
		})
	}

	let changers = document.querySelectorAll('.isBoughtChanger');
	changers.forEach(item => {
		item.addEventListener('click', () => {
			changeIsBought(item);
		})
	})

	function makeid(length) {
		var result           = '';
		var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		var charactersLength = characters.length;
		for ( var i = 0; i < length; i++ ) {
			result += characters.charAt(Math.floor(Math.random() *
				charactersLength));
		}
		return result;
	}

	function deleteRecord (item) {
		let id = item.id;
		console.log(id);
		$.ajax({
			method: "POST",
			url: "/table/deleteRecord",
			data: { id: id }
		})

		item.closest('tr').remove();

		records = records.filter(item => {
			return item.tmp_id !== id;
		})
	}


	$(document).ready(function() {

		$('p.addNote').on('click', function () {
			let data = {
				name: $('input.noteName').val(),
				catid: $('select.catName option:selected').val(),
				catname: $('select.catName option:selected').text(),
				is_bought: $('input.is_bought').is(':checked') ? '1' : '0',
				tmp_id: makeid(30)
			}
			let example = `
			<tr class="table__item">
					<td>${data['name']}</td>
					<td>${data['catname']}</td>
					<td>
						<span class="isBoughtChanger">
							${data['is_bought'] === '1' ? 'True' : 'False'}
						</span>
					</td>
					<td>
						<span onclick="deleteRecord(this)" class="deleteRecord" id='${data['tmp_id']}'>
						Delete
						</span>
					</td>
					<td>Refactor</td>
			</tr>
			`
			if (data['name'] != '' && data['catid'] != '') {
				$.ajax({
					method: "POST",
					url: "/table/insertRecord",
					data: { data }
				})
				$('tbody').last().append(example);
			}
			records.push(data);

		})

		$('p.addCategory').on('click', function () {
			let сatnameVal = $('input.catName').val();
			console.log(сatnameVal);
			if (сatnameVal != '') {
				$.ajax({
					method: "POST",
					url: "/table/insertCategory",
					data: { catname: сatnameVal }
				})
				$.ajax({
					method: "POST",
					url: "/table/getCats",

				})
			}
		})

		$('span.deleteRecord').on('click', (e) => {
			deleteRecord(e.currentTarget);
		});
	})

	function changeField(arr, tmp_id, newState) {
		arr.forEach(item => {
			if (item.tmp_id === tmp_id) {
				item.is_bought = newState;
				console.log(records)
			}
		})
		return arr;
	}



</script>

</body>
</html>
