<?php
class UbCallbackAddUser implements UbCallbackAction {
	function execute($userId, $object, $userbot) {


		$code = $object['chat'];
		require_once(CLASSES_PATH . "Ub/BindManager.php");
		$bManager = new UbBindManager();
		$chat = $bManager->getByUserChat($userId, $code);

		if (!$chat) {
			UbUtil::echoJson(UbUtil::buildErrorResponse('error', 'no chat bind', UB_ERROR_NO_CHAT));
			return;
		}

		require_once(CLASSES_PATH . "Ub/VkApi.php");
		$vk = new UbVkApi($userbot['token']);
		$res = $vk->messagesAddChatUser($object['user_id'], $chat['id_chat']);
		if (isset($res['error'])) {
			$peerId = UbVkApi::chat2PeerId($chat['id_chat']);
			$error = UbUtil::getVkErrorText($res['error']);
			$vk->messagesSend($peerId, UB_ICON_WARN . ' ' . $error);
			//UbUtil::echoJson(UbUtil::errorVkResponse($res['error']));
		}

		echo 'ok';
	}
}