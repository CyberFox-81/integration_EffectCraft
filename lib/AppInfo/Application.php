<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Britton Couchman <brittondcouchman@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\EffectCraftIntegration\AppInfo;

use OCA\Files\Event\LoadAdditionalScriptsEvent;
use OCA\EffectCraftIntegration\Listener\LoadFilesScripts;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;

class Application extends App implements IBootstrap {
	public const APP_ID = 'integration_effectcraft';

	/** User and app config key: open EffectCraft projects in EffectCraft when they are clicked in Files. */
	public const OPEN_BY_DEFAULT = 'open_by_default';

	/** The file types EffectCraft opens: video or EffectCraft project. Keep in sync with src/files.ts. */
	public const MIME_TYPES = [
		'video/mp4',
		'video/quicktime',
		'video/webm',
	];

	/**
	 * Extensions EffectCraft opens whatever MIME type Nextcloud gives them (formats Nextcloud doesn't
	 * know show up as application/octet-stream). Lower case, without the dot.
	 */
	public const EXTENSIONS = [
		'ecproj',
		'ecprojx',
	];

	/** Whether EffectCraft opens a file with this name and MIME type. */
	public static function opens(string $name, string $mimeType): bool {
		return in_array($mimeType, self::MIME_TYPES, true)
			|| in_array(strtolower(pathinfo($name, PATHINFO_EXTENSION)), self::EXTENSIONS, true);
	}

	public function __construct() {
		parent::__construct(self::APP_ID);
	}

	public function register(IRegistrationContext $context): void {
		$context->registerEventListener(LoadAdditionalScriptsEvent::class, LoadFilesScripts::class);
	}

	public function boot(IBootContext $context): void {
	}
}
