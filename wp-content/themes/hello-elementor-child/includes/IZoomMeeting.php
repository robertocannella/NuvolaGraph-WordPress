<?php

interface IZoomMeeting {
	public function getAgenda(): string;
	public function getDefaultPassword(): bool;
	public function getDuration(): int;
	public function getPassword(): string;
	public function isPreScheduled(): bool;
	public function getRecurrence(): array;
	public function getScheduleFor(): string;
	public function getSettings(): array;
	public function getStartTime(): string;
	public function getTemplateId(): string;
	public function getTimezone(): string;
	public function getTopic(): string;
	public function getTrackingFields(): array;
	public function getType(): int;
}

