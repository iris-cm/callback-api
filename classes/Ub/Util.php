<?php

define('UB_ICON_WARN', "⚠️");
define('UB_ICON_SUCCESS', "✅");
define('UB_ICON_SUCCESS_OFF', "❎");
define('UB_ICON_NOTICE', "📝");
define('UB_ICON_INFO', "🗓");
define('UB_ICON_DANGER', "📛");
define('UB_ICON_COMMENT', "💬");
define('UB_ICON_CONFIG', "⚙️");
define('UB_ICON_CATALOG', "🗂");
define('UB_ICON_STATS', "📊");

class UbUtil {

	public static function json(array $array) {
		return json_encode($array, JSON_UNESCAPED_UNICODE);
	}

	public static function echoJson(array $array) {
		echo json_encode($array, JSON_UNESCAPED_UNICODE);
	}

	public static function errorVkResponse(array $error) {
		return self::buildErrorResponse('vk_error', $error['error_msg'], $error['error_code']);
	}

	public static function echoErrorVkResponse($error) {
		self::echoJson(self::errorVkResponse($error));
	}

	public static function buildErrorResponse($type, $message, $code) {
		return ['response' => $type, 'error_message' => $message, 'error_code' => $code];
	}

	public static function echoError($message, $code = -1) {
		echo json_encode(self::buildErrorResponse('error', $message, $code), JSON_UNESCAPED_UNICODE);
	}

	public static function getVkErrorText($error) {
		$errorCode = $error['error_code'];
		$eMessage = $error['error_msg'];
		$errorMessage = null;
		switch ($errorCode) {
			case VK_BOT_ERROR_ACCESS_DENIED :
				if (strpos($eMessage, 'already in') !== false)
					$errorMessage = 'Пользователь уже в беседе';
				else if (strpos($eMessage, 'can\'t add this') !== false)
					$errorMessage = 'Не могу добавить. Скорее всего пользователь не в моих друзьях.';
			break;
			case VK_BOT_ERROR_CANT_DELETE_FOR_ALL_USERS : $errorMessage = 'Невозможно удалить для всех пользователей.' . PHP_EOL . 'Возможно удаляющий не имеет прав администратора или удаляемые сообщения принадлежат администратору.'; break;
			default : $errorMessage = ' Ошибка ВК (' . $errorCode . ')'; break;
		}
		return $errorMessage;
	}
}