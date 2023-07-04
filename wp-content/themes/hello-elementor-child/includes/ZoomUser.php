<?php
include_once 'IZoomUser.php';


class ZoomUser implements IZoomUser {

	public function getId(): string {
		return $this->id;
	}

	/**
	 * @return mixed
	 */
	public function getFirstName(): string {
		return $this->firstName;
	}

	/**
	 * @return mixed
	 */
	public function getLastName(): string {
		return $this->lastName;
	}

	/**
	 * @return mixed
	 */
	public function getUserCreatedAt(): string {
		return $this->userCreatedAt;
	}

	/**
	 * @return mixed
	 */
	public function getDisplayName(): string {
		return $this->displayName;
	}

	/**
	 * @return mixed
	 */
	public function getEmail(): string {
		return $this->email;
	}

	/**
	 * @return mixed
	 */
	public function getType(): int {
		return $this->type;
	}

	/**
	 * @return mixed
	 */
	public function getRoleName(): string {
		return $this->roleName;
	}

	/**
	 * @return mixed
	 */
	public function getPmi(): int {
		return $this->pmi;
	}

	/**
	 * @return mixed
	 */
	public function isUsingPmi(): bool {
		return $this->usePmi;
	}

	/**
	 * @return mixed
	 */
	public function getPersonalMeetingUrl(): string {
		return $this->personalMeetingUrl;
	}

	/**
	 * @return mixed
	 */
	public function getTimezone(): string {
		return $this->timezone;
	}

	/**
	 * @return mixed
	 */
	public function isVerified(): bool {
		return $this->verified;
	}

	/**
	 * @return mixed
	 */
	public function getDept(): string {
		return $this->dept;
	}

	/**
	 * @return mixed
	 */
	public function getCreatedAt(): string {
		return $this->createdAt;
	}

	/**
	 * @return mixed
	 */
	public function getLastLoginTime(): string {
		return $this->lastLoginTime;
	}

	/**
	 * @return mixed
	 */
	public function getLastClientVersion(): string {
		return $this->lastClientVersion;
	}

	/**
	 * @return mixed
	 */
	public function getPicUrl(): string {
		return $this->picUrl;
	}

	/**
	 * @return mixed
	 */
	public function getCmsUserId(): string {
		return $this->cmsUserId;
	}

	/**
	 * @return mixed
	 */
	public function getJid(): string {
		return $this->jid;
	}

	/**
	 * @return mixed
	 */
	public function getGroupIds(): array {
		return $this->groupIds;
	}

	/**
	 * @return mixed
	 */
	public function getImGroupIds(): array {
		return $this->imGroupIds;
	}

	/**
	 * @return mixed
	 */
	public function getAccountId(): string {
		return $this->accountId;
	}

	/**
	 * @return mixed
	 */
	public function getLanguage(): string {
		return $this->language;
	}

	/**
	 * @return mixed
	 */
	public function getPhoneCountry(): string {
		return $this->phoneCountry;
	}

	/**
	 * @return mixed
	 */
	public function getPhoneNumber(): string {
		return $this->phoneNumber;
	}

	/**
	 * @return mixed
	 */
	public function getStatus(): string {
		return $this->status;
	}

	/**
	 * @return mixed
	 */
	public function getJobTitle(): string {
		return $this->jobTitle;
	}

	/**
	 * @return mixed
	 */
	public function getLocation(): string {
		return $this->location;
	}

	/**
	 * @return mixed
	 */
	public function getLoginTypes(): array {
		return $this->loginTypes;
	}

	/**
	 * @return mixed
	 */
	public function getRoleId(): string {
		return $this->roleId;
	}

	/**
	 * @return mixed
	 */
	public function getCluster(): string {
		return $this->cluster;
	}

	private mixed $id;
	private mixed $firstName;
	private mixed $lastName;
	private mixed $displayName;
	private mixed $email;
	private mixed $type;
	private mixed $roleName;
	private mixed $pmi;
	private mixed $usePmi;
	private mixed $personalMeetingUrl;
	private mixed $timezone;
	private mixed $verified;
	private mixed $dept;
	private mixed $createdAt;
	private mixed $lastLoginTime;
	private mixed $lastClientVersion;
	private mixed $picUrl;
	private mixed $cmsUserId;
	private mixed $jid;
	private mixed $groupIds;
	private mixed $imGroupIds;
	private mixed $accountId;
	private mixed $language;
	private mixed $phoneCountry;
	private mixed $phoneNumber;
	private mixed $status;
	private mixed $jobTitle;
	private mixed $location;
	private mixed $loginTypes;
	private mixed $roleId;
	private mixed $cluster;
	private mixed $userCreatedAt;

	public function __construct( $data ) {
		$this->id                 = $data->id;
		$this->firstName          = $data->first_name;
		$this->lastName           = $data->last_name;
		$this->displayName        = $data->display_name;
		$this->email              = $data->email;
		$this->type               = $data->type;
		$this->roleName           = $data->role_name;
		$this->pmi                = $data->pmi;
		$this->usePmi             = $data->use_pmi;
		$this->personalMeetingUrl = $data->personal_meeting_url;
		$this->timezone           = $data->timezone;
		$this->verified           = $data->verified;
		$this->dept               = $data->dept;
		$this->createdAt          = $data->created_at;
		$this->lastLoginTime      = $data->last_login_time;
		$this->lastClientVersion  = $data->last_client_version;
		$this->picUrl             = $data->pic_url;
		$this->cmsUserId          = $data->cms_user_id;
		$this->jid                = $data->jid;
		$this->groupIds           = $data->group_ids;
		$this->imGroupIds         = $data->im_group_ids;
		$this->accountId          = $data->account_id;
		$this->language           = $data->language;
		$this->phoneCountry       = $data->phone_country;
		$this->phoneNumber        = $data->phone_number;
		$this->status             = $data->status;
		$this->jobTitle           = $data->job_title;
		$this->location           = $data->location;
		$this->loginTypes         = $data->login_types;
		$this->roleId             = $data->role_id;
		$this->cluster            = $data->cluster;
		$this->userCreatedAt      = $data->user_created_at;
	}


}
