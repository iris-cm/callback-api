<?php
class UbCallbackDeleteMessages implements UbCallbackAction {
	function execute($userId, $object, $userbot) {

		$code = $object['chat'];
		require_once(CLASSES_PATH . "Ub/BindManager.php");
		$bManager = new UbBindManager();
		$chat = $bManager->getByUserChat($userId, $code);

		if (!$chat) {
			UbUtil::echoJson(UbUtil::buildErrorResponse('error', 'no chat bind', UB_ERROR_NO_CHAT));
			return;
		}

		$localIds = $object['local_ids'];

		if (!count($localIds)) {
			echo 'ok';
			return;
		}
		$chatId = $chat['id_chat'];

		$vk = new UbVkApi($userbot['token']);
		$messages = $vk->messagesGetByConversationMessageId(UbVkApi::chat2PeerId($chatId), $localIds);
		if (isset($messages['error'])) {
			$error = UbUtil::getVkErrorText($messages['error']);
			$vk->chatMessage($chatId, UB_ICON_WARN . ' ' . $error);
			echo 'ok';
			//UbUtil::echoErrorVkResponse($messages['error']);
			return;
		}

		$messages = $messages['response']['items'];
		$ids = [];
		foreach ($messages as $m)
			$ids[] = $m['id'];

		if (!count($ids)) {
			//UbUtil::echoError('nothing to delete', 11);
			return;
		}

		$res = $vk->messagesDelete($ids, true);
		if (isset($res['error'])) {
			$error = UbUtil::getVkErrorText($res['error']);
			$vk->chatMessage($chatId, UB_ICON_WARN . ' ' . $error);
			echo 'ok';
			//UbUtil::echoErrorVkResponse($res['error']);
			return;
		}
		$vk->chatMessage($chatId, UB_ICON_SUCCESS . ' Сообщения удалены');
		echo 'ok';
	}
}