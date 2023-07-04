<?php

class ZoomRegistrant implements IZoomRegistrant {
	private string $id;
	private string $address;
	private string $city;
	private string $comments;
	private string $country;
	private array $customQuestions;
	private string $email;
	private string $firstName;
	private string $industry;
	private string $jobTitle;
	private string $lastName;
	private mixed $numberOfEmployees;
	private string $organization;
	private string $phone;
	private mixed $purchasingTimeFrame;
	private mixed $roleInPurchaseProcess;
	private string $state;
	private enum $status;
	private string $zip;
	private string $createTime;
	private string $joinUrl;
	private int $participantPinCode;

	public function __construct($data) {
		$this->id = $data['id'];
		$this->address = $data['address'];
		$this->city = $data['city'];
		$this->comments = $data['comments'];
		$this->country = $data['country'];
		$this->customQuestions = $data['custom_questions'];
		$this->email = $data['email'];
		$this->firstName = $data['first_name'];
		$this->industry = $data['industry'];
		$this->jobTitle = $data['job_title'];
		$this->lastName = $data['last_name'];
		$this->numberOfEmployees = $data['no_of_employees'];
		$this->organization = $data['org'];
		$this->phone = $data['phone'];
		$this->purchasingTimeFrame = $data['purchasing_time_frame'];
		$this->roleInPurchaseProcess = $data['role_in_purchase_process'];
		$this->state = $data['state'];
		$this->status = $data['status'];
		$this->zip = $data['zip'];
		$this->createTime = $data['create_time'];
		$this->joinUrl = $data['join_url'];
		$this->participantPinCode = $data['participant_pin_code'];
	}

	public function getId(): string {
		return $this->id;
	}

	public function getAddress(): string {
		return $this->address;
	}

	public function getCity(): string {
		return $this->city;
	}

	public function getComments(): string {
		return $this->comments;
	}

	public function getCountry(): string {
		return $this->country;
	}

	public function getCustomQuestions(): array {
		return $this->customQuestions;
	}

	public function getEmail(): string {
		return $this->email;
	}

	public function getFirstName(): string {
		return $this->firstName;
	}

	public function getIndustry(): string {
		return $this->industry;
	}

	public function getJobTitle(): string {
		return $this->jobTitle;
	}

	public function getLastName(): string {
		return $this->lastName;
	}

	public function getNumberOfEmployees(): string {
		return $this->numberOfEmployees;
	}

	public function getOrganization(): string {
		return $this->organization;
	}

	public function getPhone(): string {
		return $this->phone;
	}

	public function getPurchasingTimeFrame(): string {
		return $this->purchasingTimeFrame;
	}

	public function getRoleInPurchaseProcess(): string {
		return $this->roleInPurchaseProcess;
	}

	public function getState(): string {
		return $this->state;
	}

	public function getStatus(): string {
		return $this->status;
	}

	public function getZip(): string {
		return $this->zip;
	}

	public function getCreateTime(): string {
		return $this->createTime;
	}

	public function getJoinUrl(): string {
		return $this->joinUrl;
	}

	public function getParticipantPinCode(): int {
		return $this->participantPinCode;
	}
}
