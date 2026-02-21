<?php

	//字符编码设定
	echo "<meta charset=\"utf-8\">";

	$rpx_file = "rptMeisai.rpx";

	//输出指定rpx文件的节点信息
	//目的是让开发人员对rpx文件的结构及帐票上各个项目的具体信息有直观的了解
	//以便用tcpdf生成pdf时提供方便（检索等等），提高效率
	//若不需要，注释掉
	show_rpx_node_info($rpx_file);

	/**
	 * 输出指定rpx文件的节点信息
	 */
	function show_rpx_node_info($rpx_file = '')
	{
		//常量定义
		$NL = "<br/>";
		$SPACE = "&nbsp;";
		$INDENT = $SPACE . $SPACE . $SPACE . $SPACE;

		//确认文件是否存在
		if (file_exists($rpx_file))
		{
			//把rpx文件导入，生成xml对象
			$xml = simplexml_load_file($rpx_file);

			//1级节点名
			echo "1级节点名";
			echo $NL;
			echo $INDENT, $xml -> getName();
			echo $NL;

			//1级节点的属性
			echo "1级节点的属性";
			echo $NL;
			foreach ($xml->attributes() as $key => $value)
			{
				echo $key, '="', $value, "\"";
				echo $NL;
			};

			echo $NL;

			foreach ($xml->children() as $child2)
			{
				//2级节点名
				echo "2级节点名";
				echo $NL;
				echo $INDENT, $child2 -> getName();
				echo $NL;

				//2级节点的属性
				echo "2级节点的属性";
				echo $NL;
				foreach ($child2->attributes() as $key => $value)
				{
					echo $INDENT, $key, '="', $value, "\"";
					echo $NL;
				};

				//3级节点
				echo "3级节点";
				echo $NL;
				foreach ($child2->children() as $child3)
				{
					//3级节点名
					echo "3级节点名";
					echo $NL;
					echo $INDENT, $child3 -> getName();
					echo $NL;

					//3级节点的属性
					echo "3级节点的属性";
					echo $NL;
					foreach ($child3->attributes() as $key => $value)
					{
						echo $INDENT, $key, '="', $value, "\"";
						echo $NL;
					};

					//4级节点
					echo "4级节点";
					echo $NL;
					foreach ($child3->children() as $child4)
					{
						//4级节点名
						echo "4级节点名";
						echo $NL;
						echo $INDENT, $child4 -> getName();
						echo $NL;

						//4级节点的属性
						echo "4级节点的属性";
						echo $NL;
						foreach ($child4->attributes() as $key => $value)
						{
							echo $INDENT, $key, '="', $value, "\"";
							echo $NL;
						};

					};
				};
			};
		}
		else
		{
			exit('Error.');
		};
	};
?>

