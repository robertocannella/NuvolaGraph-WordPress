<?php

interface IZoomUser {
	public function getId(): string;
	public function getFirstName(): string;
	public function getLastName(): string;
	public function getDisplayName(): string;
	public function getEmail(): string;
	public function getType(): int;
	public function getRoleName(): string;
	public function getPmi(): int;
	public function isUsingPmi(): bool;
	public function getPersonalMeetingUrl(): string;
	public function getTimezone(): string;
	public function isVerified(): bool;
	public function getDept(): string;
	public function getCreatedAt(): string;
	public function getLastLoginTime(): string;
	public function getLastClientVersion(): string;
	public function getPicUrl(): string;
	public function getCmsUserId(): string;
	public function getJid(): string;
	public function getGroupIds(): array;
	public function getImGroupIds(): array;
	public function getAccountId(): string;
	public function getLanguage(): string;
	public function getPhoneCountry(): string;
	public function getPhoneNumber(): string;
	public function getStatus(): string;
	public function getJobTitle(): string;
	public function getLocation(): string;
	public function getLoginTypes(): array;
	public function getRoleId(): string;
	public function getCluster(): string;
	public function getUserCreatedAt(): string;
}