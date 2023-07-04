<?php

require_once 'IZoomMeeting.php';

class ZoomMeeting implements IZoomMeeting {
	public string|null $agenda;
	public bool|null $defaultPassword;
	public int|null $duration;
	public string|null $password;
	public bool|null $preSchedule;
	public mixed $recurrence;
	public string|null $scheduleFor;
	public mixed $settings;
	public string|null $startTime;
	public string|null $templateId;
	public string|null $timezone;
	public string|null $topic;
	public mixed $trackingFields;
	public int|null $type;


	public function __construct($data = null) {
		$this->agenda          = $data->agenda ?? null;
		$this->defaultPassword = $data->defaultPassword ?? null;
		$this->duration        = $data->duration ?? null;
		$this->password        = $data->password ?? false;
		$this->preSchedule     = $data->pre_chedule ?? null;
		$this->recurrence      = $data->recurrence ?? null;
		$this->scheduleFor     = $data->schedule_for ?? null;
		$this->settings        = $data->settings ?? null;
		$this->startTime       = $data->start_time ?? null;
		$this->templateId      = $data->template_id ?? null;
		$this->timezone        = $data->timezone ?? null;
		$this->topic           = $data->topic ?? null;
		$this->trackingFields  = $data->tracking_fields ?? null;
		$this->type            = $data->type ?? null;
	}


	public function getAgenda(): string {
		return $this->agenda;
	}

	public function getDefaultPassword(): bool {
		return $this->defaultPassword;
	}

	public function getDuration(): int {
		return $this->duration;
	}

	public function getPassword(): string {
		return $this->password;
	}

	public function isPreScheduled(): bool {
		return $this->preSchedule;
	}

	public function getRecurrence(): array {
		return $this->recurrence;
	}

	public function getScheduleFor(): string {
		return $this->scheduleFor;
	}

	public function getSettings(): array {
		return $this->settings;
	}

	public function getStartTime(): string {
		return $this->startTime;
	}

	public function getTemplateId(): string {
		return $this->templateId;
	}

	public function getTimezone(): string {
		return  $this->timezone;
	}

	public function getTopic(): string {
		return $this->topic;
	}

	public function getTrackingFields(): array {
		return $this->trackingFields;
	}

	public function getType(): int {
		return $this->type;
	}
}