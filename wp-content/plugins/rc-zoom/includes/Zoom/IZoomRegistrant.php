<?php

interface IZoomRegistrant {
	public function getId(): string;
	public function getAddress(): string;
	public function getCity(): string;
	public function getComments(): string;
	public function getCountry(): string;
	public function getCustomQuestions(): array;
	public function getEmail(): string;
	public function getFirstName(): string;
	public function getIndustry(): string;
	public function getJobTitle(): string;
	public function getLastName(): string;
	public function getNumberOfEmployees(): string;
	public function getOrganization(): string;
	public function getPhone(): string;
	public function getPurchasingTimeFrame(): string;
	public function getRoleInPurchaseProcess(): string;
	public function getState(): string;
	public function getStatus(): string;
	public function getZip(): string;
	public function getCreateTime(): string;
	public function getJoinUrl(): string;
	public function getParticipantPinCode(): int;
}