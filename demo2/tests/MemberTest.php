<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use App\Member;
use Faker\Factory;

class MemberTest extends TestCase
{
	use DatabaseMigrations;
	use WithoutMiddleware;


	public function testListMember()
	{
		$response = $this->get('list');
		$this->assertResponseStatus(200);
	}


	public function testAddMember()
	{
		$member = [
			'name' => 'Tran Hong Nhung',
			'address' => 'Noi Nay Co Anh',
			'age' => '11'
		];
		$response = $this->call('POST', 'add', $member);
		$success = $response->getContent();
		$this->assertEquals($success,200);
		$this->seeInDatabase('members', [
			'name' => $member['name'],
			'address' => $member['address'],
			'age' => $member['age'],
		]);
	}

	public function testAddMemberHasNameNumeric()
	{
		$member = [
			'name' => 'Hong Hanh 123',
			'address' => 'aa',
			'age' => '11'
		];
		$response = $this->call('POST', 'add', $member);
		$error = $response->exception->validator->messages()->first();
		$this->assertEquals('The Name can contain only Alphabetic characters.',$error);
	}
	public function testAddMemberHasNameScript()
	{
		$member = [
			'name' => '<script>alert("Boom Boom");</script>',
			'address' => 'aa',
			'age' => '11'
		];
		$response = $this->call('POST', 'add', $member);
		$error = $response->exception->validator->messages()->first();
		$this->assertEquals('The Name can contain only Alphabetic characters.',$error);
	}

	public function testAddMemberHasAddressScript()
	{
		$photo = new UploadedFile(base_path('public\photo\01.jpg'),
			'01.jpg', 'image/jpg', 111, $error = null, $test = true);
		$member = [
			'name' => 'Nhung',
			'address' => '<script>alert("Boom Boom");</script>',
			'age' => 33,
			'photo' => $photo
		];
		$response = $this->call('POST', 'add', $member);
		$success = $response->getContent();
		$this->assertEquals($success,200);
		$this->seeInDatabase('members', [
			'name' => $member['name'],
			'address' => $member['address'],
			'age' => $member['age'],
		]);
	}

	
	public function testAddMemberHasPhotoJPG()
	{
		$photo = new UploadedFile(base_path('public\photo\01.jpg'),
			'01.jpg', 'image/jpg', 111, $error = null, $test = true);
		$member = [
			'name' => 'Nhung',
			'address' => 'Ha Tay',
			'age' => 33,
			'photo' => $photo
		];
		$response = $this->call('POST', 'add', $member);
		$success = $response->getContent();
		$this->assertEquals($success,200);
		$this->seeInDatabase('members', [
			'name' => $member['name'],
			'address' => $member['address'],
			'age' => $member['age'],
		]);
	}
	public function testAddMemberHasPhotoPNG()
	{
		$photo = new UploadedFile(base_path('public\photo\010.png'),
			'010.png', 'image/png', 111, $error = null, $test = true);
		$member = [
			'name' => 'Nhung',
			'address' => 'Ha Tay',
			'age' => 33,
			'photo' => $photo
		];
		$response = $this->call('POST', 'add', $member);
		$success = $response->getContent();
		$this->assertEquals($success,200);
		$this->seeInDatabase('members', [
			'name' => $member['name'],
			'address' => $member['address'],
			'age' => $member['age'],
		]);
	}

	public function testAddMemberHasPhotoGIF()
	{
		$photo = new UploadedFile(base_path('public\photo\011.gif'),
			'011.gif', 'image/gif', 111, $error = null, $test = true);
		$member = [
			'name' => 'Nhung',
			'address' => 'Ha Tay',
			'age' => 33,
			'photo' => $photo
		];
		$response = $this->call('POST', 'add', $member);
		$success = $response->getContent();
		$this->assertEquals($success,200);
		$this->seeInDatabase('members', [
			'name' => $member['name'],
			'address' => $member['address'],
			'age' => $member['age'],
		]);
	}

	public function testAddMemberNullName(){
		
		$member = [
			'name' => '',
			'address' => 'Maxtcova',
			'age' => 69
		];
		$response = $this->call('POST', 'add', $member);
		$error = $response->exception->validator->messages()->first();
		$this->assertEquals('The Name field is required.',$error);
	}

	public function testAddMemberNullAddress()
	{
		$member = [
			'name' => 'Hong Nhung',
			'address' => '',
			'age' => 23,
		];
		$response = $this->call('POST', 'add', $member);
		$error = $response->exception->validator->messages()->first();
		$this->assertEquals('The Address field is required.',$error);
	}

	public function testAddMemberNullAge()
	{
		$member = [
			'name' => 'Hong Nhung',
			'address' => 'Truong Son Tay',
			'age' => '',
		];
		$response = $this->call('POST', 'add', $member);
		$error = $response->exception->validator->messages()->first();
		$this->assertEquals('The Age field is required.',$error);
	}

	public function testAddAgeNotNumeric()
	{   
		$member = [
			'name' => 'Member',
			'address' => 'Macao',
			'age' => 'date'
		];
		$response = $this->call('POST', 'add', $member);
		$error = $response->exception->validator->messages()->first();
		$this->assertEquals('The Age must be a number.',$error);
	}

	public function testAddAgeMax2Characters()
	{   
		$member = [
			'name' => 'Member',
			'address' => 'Macao',
			'age' => 223
		];
		$response = $this->call('POST', 'add', $member);
		$error = $response->exception->validator->messages()->first();
		$this->assertEquals('Maximum 2 characters',$error);
	}

	public function testAddName100Characters()
	{
		$member = [
			'name' => 'qwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiop',
			'address' => 'Hang Gai',
			'age' => 55,
		];
		$response = $this->call('POST', 'add', $member);
		$success = $response->getContent();
		$this->assertEquals($success,200);
		$this->seeInDatabase('members', [
			'name' => $member['name'],
			'address' => $member['address'],
			'age' => $member['age'],
		]);
	}

	public function testAddNameGreaterThan100Characters()
	{
		$member = [
			'name' => 'qwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopQ',
			'address' => 'Hai Phong',
			'age' => 55,
		];
		$response = $this->call('POST', 'add', $member);
		$error = $response->exception->validator->messages()->first();
		$this->assertEquals('The name may not be greater than 100 characters.',$error);
	}

	public function testAddAddress300Characters()
	{
		$member = [
			'name' => 'Hoang Anh',
			'address' => 'qwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiop',
			'age' => 55,
		];
		$response = $this->call('POST', 'add', $member);
		$success = $response->getContent();
		$this->assertEquals($success,200);
		$this->seeInDatabase('members', [
			'name' => $member['name'],
			'address' => $member['address'],
			'age' => $member['age'],
		]);
	}

	public function testAddAddressGreaterThan300Characters()
	{
		$member = [
			'name' => 'What Your Name',
			'address' => 'qwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopE',
			'age' => 66,
		];
		$response = $this->call('POST', 'add', $member);
		$error = $response->exception->validator->messages()->first();
		$this->assertEquals('The name may not be greater than 300 characters.',$error);
	}


	public function testAddImageGreaterThan10Mb()
	{
		$photo = new UploadedFile(base_path('public\photo\img10MB.jpg'),
			'img10MB.jpg', 'image/jpg', 111, $error = null, $test = true);
		$member = [
			'name' => 'Tran Hong Nhung ',
			'address' => 'Ha Nam, Ha Bac',
			'age' => 23,
			'photo' => $photo
		];
		$response = $this->call('POST', 'add', $member);
		$error = $response->exception->validator->messages()->first();
		$this->assertEquals('The photo may not be greater than 10240 kilobytes.',$error);
	}

	public function testAddImageNotValidExtension()
	{
		$photo = new UploadedFile(base_path('public\photo\image.bmp'),
			'image.bmp', 'image/jpg', 111, $error = null, $test = true);
		$member = [
			'name' => 'Tran Hoang ',
			'address' => 'Ha Nam',
			'age' => 23,
			'photo' => $photo
		];
		$response = $this->call('POST', 'add', $member);
		$error = $response->exception->validator->messages()->first();
		$this->assertEquals('Photo only allow JPG, GIF, and PNG filetypes.',$error);
	}

	public function testAddImageNotValidImage()
	{
		$photo = new UploadedFile(base_path('public\photo\not_img.png'),
			'not_img.png', 'image/png', 111, $error = null, $test = true);
		$member = [
			'name' => 'Ngoc Anh',
			'address' => 'Effel',
			'age' => 23,
			'photo' => $photo
		];
		$response = $this->call('POST', 'add', $member);
		$error = $response->exception->validator->messages()->first();
		$this->assertEquals('Uploaded file is not a valid image',$error);
	}

	public function testDeleteMember()
	{
		$member = Factory(Member::class)->create([
			'name' => 'Administrator',
			'address' => 'Land-Mark',
			'age' => 21
		]);
		$response = $this->call('GET', 'delete/'.$member->id);
		$this->assertResponseStatus(200);
		$this->notSeeInDatabase('members',
			[
				'name' => 'Administrator',
				'address' => 'Land-Mark',
				'age' => 21
			]);
	}
	
	public function testDeleteMemberHasPhoto()
	{
		$photo = new UploadedFile(base_path('public\photo\01.jpg'),
			'01.jpg', 'image/jpg', 111, $error = null, $test = true);
		$member = Factory(Member::class)->create([
			'name' => 'Administrator',
			'address' => 'Land-Mark',
			'age' => 21,
			'photo'=>$photo
		]);
		$response = $this->call('GET', 'delete/'.$member->id);
		$this->assertResponseStatus(200);
		$this->notSeeInDatabase('members',
			[
				'name' => 'Administrator',
				'address' => 'Land-Mark',
				'age' => 21
			]);
	}


	public function testEditMember()
	{
		$member = Factory(Member::class)->create([
			'name' => 'Hong Nhung',
			'address' => 'London',
			'age' => 23
		]);
		$update = [
			'name' => 'Nam Trung',
			'address' => 'Sai Gon',
			'age' => 21
		];
		$response = $this->call('POST','edit/'.$member->id, $update);
		$success = $response->getContent();
		$this->assertEquals($success,200);
	}

	public function testEditMemberHasPhotoJPG()
	{
		$photo = new UploadedFile(base_path('public\photo\01.jpg'),
			'01.jpg', 'image/jpg', 111, $error = null, $test = true);
		$member = Factory(Member::class)->create([
			'name' => 'Administrator',
			'address' => 'Land-Mark',
			'age' => 21,
			'photo'=>$photo
		]);
		$update = [
			'name' => 'Nam Trung',
			'address' => 'Sai Gon',
			'age' => 21
		];
		$response = $this->call('POST','edit/'.$member->id, $update);
		$success = $response->getContent();
		$this->assertEquals($success,200);
	}

	public function testEditMemberHasPhotoPNG()
	{
		$photo = new UploadedFile(base_path('public\photo\010.png'),
			'010.png', 'image/png', 111, $error = null, $test = true);
		$member = Factory(Member::class)->create([
			'name' => 'Administrator',
			'address' => 'Land-Mark',
			'age' => 21,
			'photo'=>$photo
		]);
		$update = [
			'name' => 'Nam Trung',
			'address' => 'Sai Gon',
			'age' => 21
		];
		$response = $this->call('POST','edit/'.$member->id, $update);
		$success = $response->getContent();
		$this->assertEquals($success,200);
	}

	public function testEditMemberHasPhotoGIF()
	{
		$photo = new UploadedFile(base_path('public\photo\011.gif'),
			'011.gif', 'image/gif', 111, $error = null, $test = true);
		$member = Factory(Member::class)->create([
			'name' => 'Administrator',
			'address' => 'Land-Mark',
			'age' => 21,
			'photo'=>$photo
		]);
		$update = [
			'name' => 'Nam Trung',
			'address' => 'Sai Gon',
			'age' => 21
		];
		$response = $this->call('POST','edit/'.$member->id, $update);
		$success = $response->getContent();
		$this->assertEquals($success,200);
	}

	public function testEditMemberNameNull()
	{
		$member = Factory(Member::class)->create([
			'name' => 'Hong Nhung',
			'address' => 'London',
			'age' => 23
		]);
		$update = [
			'name' => '',
			'address' => 'Sai Gon',
			'age' => 21
		];
		$response = $this->call('POST','edit/'.$member->id, $update);
		$error = $response->exception->validator->messages()->first();
		$this->assertEquals('The Name field is required.',$error);
	}

	public function testEditMemberNameHasNumeric()
	{
		$member = Factory(Member::class)->create([
			'name' => 'Hong Nhung',
			'address' => 'London',
			'age' => 23
		]);
		$update = [
			'name' => 'Nam Trung 01',
			'address' => 'Sai Gon',
			'age' => 21
		];
		$response = $this->call('POST','edit/'.$member->id, $update);
		$error = $response->exception->validator->messages()->first();
		$this->assertEquals('The Name can contain only Alphabetic characters.',$error);
	}

	public function testEditMemberName100Characters()
	{
		$member = Factory(Member::class)->create([
			'name' => 'Hong Nhung',
			'address' => 'London',
			'age' => 23
		]);
		$update = [
			'name' => 'qwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiop',
			'address' => 'Sai Gon',
			'age' => 21
		];
		$response = $this->call('POST','edit/'.$member->id, $update);
		$success = $response->getContent();
		$this->assertEquals($success,200);
	}

	public function testEditMemberNameGreaterThan100Characters()
	{
		$member = Factory(Member::class)->create([
			'name' => 'Hong Nhung',
			'address' => 'London',
			'age' => 23
		]);
		$update = [
			'name' => 'qwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopA',
			'address' => 'Sai Gon',
			'age' => 21
		];
		$response = $this->call('POST','edit/'.$member->id, $update);
		$error = $response->exception->validator->messages()->first();
		$this->assertEquals('The name may not be greater than 100 characters.',$error);
	}

	public function testEditMemberAddressNull()
	{
		$member = Factory(Member::class)->create([
			'name' => 'Hong Nhung',
			'address' => 'London',
			'age' => 23
		]);
		$update = [
			'name' => 'Nam Trung',
			'address' => '',
			'age' => 21
		];
		$response = $this->call('POST','edit/'.$member->id, $update);
		$error = $response->exception->validator->messages()->first();
		$this->assertEquals('The Address field is required.',$error);
	}

	public function testEditMemberAddress300Characters()
	{
		$member = Factory(Member::class)->create([
			'name' => 'Hong Nhung',
			'address' => 'London',
			'age' => 23
		]);
		$update = [
			'name' => 'Hong Anh',
			'address' => 'qwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiop',
			'age' => 21
		];
		$response = $this->call('POST','edit/'.$member->id, $update);
		$success = $response->getContent();
		$this->assertEquals($success,200);
	}

	public function testEditMemberAddressGreaterThan300Characters()
	{
		$member = Factory(Member::class)->create([
			'name' => 'Hong Nhung',
			'address' => 'London',
			'age' => 23
		]);
		$update = [
			'name' => 'Hong Anh',
			'address' => 'qwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopqwertyuiopAA',
			'age' => 21
		];
		$response = $this->call('POST','edit/'.$member->id, $update);
		$error = $response->exception->validator->messages()->first();
		$this->assertEquals('The name may not be greater than 300 characters.',$error);
	}

	public function testEditMemberAgesNull()
	{
		$member = Factory(Member::class)->create([
			'name' => 'Hong Nhung',
			'address' => 'London',
			'age' => 23
		]);
		$update = [
			'name' => 'Nam Trung',
			'address' => 'Ha Noi',
			'age' => ''
		];
		$response = $this->call('POST','edit/'.$member->id, $update);
		$error = $response->exception->validator->messages()->first();
		$this->assertEquals('The Age field is required.',$error);
	}

	public function testEditAgeNotNumeric()
	{
		$member = Factory(Member::class)->create([
			'name' => 'Ronaldo',
			'address' => 'Ciao',
			'age' => 23,
		]);
		$update = [
			'name' => 'Member',
			'address' => 'Macao',
			'age' => 'date'
		];
		$response = $this->call('POST','edit/'.$member->id, $update);
		$error = $response->exception->validator->messages()->first();
		$this->assertEquals('The Age must be a number.',$error);
	}

	public function testEditAgeMax2Characters()
	{   
		$member = Factory(Member::class)->create([
			'name' => 'Ronaldo',
			'address' => 'Ciao',
			'age' => 23,
		]);
		$update = [
			'name' => 'Member',
			'address' => 'Macao',
			'age' => 232
		];
		$response = $this->call('POST','edit/'.$member->id, $update);
		$error = $response->exception->validator->messages()->first();
		$this->assertEquals('Maximum 2 characters',$error);
	}

	public function testEditImageNotValidImage()
	{
		$photo = new UploadedFile(base_path('public\photo\not_img.png'),
			'not_img.png', 'image/png', 111, $error = null, $test = true);
		$member = Factory(Member::class)->create([
			'name' => 'Ngoc Anh',
			'address' => 'Effel',
			'age' => 23,
			'photo' => $photo
		]);
		$update = [
			'name' => 'Member',
			'address' => 'Macao',
			'age' => 23,
			'photo' => $photo
		];
		$response = $this->call('POST','edit/'.$member->id, $update);
		$error = $response->exception->validator->messages()->first();
		$this->assertEquals('Uploaded file is not a valid image',$error);
	}

	public function testEditImageNotValidExtension()
	{
		$photo = new UploadedFile(base_path('public\photo\image.bmp'),
			'image.bmp', 'image/jpg', 111, $error = null, $test = true);
		$member = Factory(Member::class)->create([
			'name' => 'Ngoc Anh',
			'address' => 'Effel',
			'age' => 23,
			'photo' => $photo
		]);
		$update = [
			'name' => 'Member',
			'address' => 'Macao',
			'age' => 23,
			'photo' => $photo
		];
		$response = $this->call('POST','edit/'.$member->id, $update);
		$error = $response->exception->validator->messages()->first();
		$this->assertEquals('Photo only allow JPG, GIF, and PNG filetypes.',$error);
	}


	public function testEditImageGreaterThan10Mb()
	{
		$photo = new UploadedFile(base_path('public\photo\img10MB.jpg'),
			'img10MB.jpg', 'image/jpg', 111, $error = null, $test = true);
		$member = Factory(Member::class)->create([
			'name' => 'Tran Hong Nhung ',
			'address' => 'Ha Nam, Ha Bac',
			'age' => 23,
			'photo' => $photo
		]);
		$update = [
			'name' => 'Member',
			'address' => 'Macao',
			'age' => 23,
			'photo' => $photo
		];
		$response = $this->call('POST','edit/'.$member->id, $update);
		$error = $response->exception->validator->messages()->first();
		$this->assertEquals('The photo may not be greater than 10240 kilobytes.',$error);
	}
	
	public function testEditMemberHasAddressScript()
	{
		$photo = new UploadedFile(base_path('public\photo\01.jpg'),
			'01.jpg', 'image/jpg', 111, $error = null, $test = true);
		$member = Factory(Member::class)->create([
			'name' => 'Nhung',
			'address' => 'Noi nay co Anh',
			'age' => 33,
			'photo' => $photo
		]);
		$update = [
			'name' => 'Nhung',
			'address' => '<script>alert("Boom Boom");</script>',
			'age' => 33,
			'photo' => $photo
		];
		$response = $this->call('POST','edit/'.$member->id, $update);
		$success = $response->getContent();
		$this->assertEquals($success,200);
	}

	public function testEditMemberHasNameScript()
	{
		$photo = new UploadedFile(base_path('public\photo\01.jpg'),
			'01.jpg', 'image/jpg', 111, $error = null, $test = true);
		$member = Factory(Member::class)->create([
			'name' => 'Nhung',
			'address' => 'Noi nay co Anh',
			'age' => 33,
			'photo' => $photo
		]);
		$update = [
			'name' => '<script>alert("Boom Boom");</script>',
			'address' => 'Ha Noi',
			'age' => 33,
			'photo' => $photo
		];
		$response = $this->call('POST','edit/'.$member->id, $update);
		$error = $response->exception->validator->messages()->first();
		$this->assertEquals('The Name can contain only Alphabetic characters.',$error);
	}



}
