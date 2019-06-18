<?php
namespace api\type;

interface NoticeType
{
	const NOTICE_TYPE_JOIN = 0;
	const NOTICE_TYPE_QUIT = 1;
	const NOTICE_TYPE_REQUEST = 2;
	const NOTICE_TYPE_REQUEST_ACCEPTED = 3;
}