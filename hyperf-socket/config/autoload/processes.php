<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    /**
     * Redis�첽����
     *
     *
     * ReloadChannelListener��
     *  ����Ϣִ�г�ʱ������Ŀ����������Ϣִ�б��жϣ����ն��ᱻ�ƶ��� timeout �����У�ֻҪ�����Ա�֤��Ϣִ�е�ԭ���ԣ�ͬһ����Ϣִ��һ�Σ���ִ�ж�Σ����ձ���һ�£��� �Ϳ��Կ������¼���������ܻ��Զ��� timeout ��������Ϣ�ƶ��� waiting �����У��ȴ��´����ѡ�
     *
     * ���������� QueueLength �¼���Ĭ��ִ�� 500 ����Ϣ�󴥷�һ�Ρ�
     */
    Hyperf\AsyncQueue\Listener\ReloadChannelListener::class,
];
