/***************************************************
             HP 39GS Repurpose Project
			              by ZephRay
		      nbzwt@live.cn  www.zephray.com
***************************************************/
#include "timer.h"
#include "interrupt.h"
#include "2410addr.h"
#include "key.h"

volatile unsigned long SysTick=0;

//PrescalerΪTim2������������Tim4���ã���������һ��
void Tim4_Init(unsigned long freq)
{
	rTCFG0  &= ~0x0000ff00;
	rTCFG0  |=  0x00000f00;
	rTCFG1  &= ~0x000f0000;
	rTCFG1  |=  0x00020000;
	rTCNTB4 = (PCLK>>7)/freq;
	rTCON &= ~0x00070000;
	
	IRQ_RegISR(14, &Tim4_IntHandler);
	IRQ_Unmask(14);
}

//on=1 ���� 0 �ر�
void Tim4_Start(unsigned char on)
{
	if (on)
	{
          rTCON &= ~0x00700000;//����趨
          rTCON |=  0x00700000;//�����Զ���װ����װ��ֵ
	  rTCON &= ~0x00200000;//��ɳ�ֵ��װ
	}else
          rTCON &= ~0x00700000;
}

__irq void Tim4_IntHandler(void)
{
  rSRCPND = 1<<14;
  KBD_Scan();
  SysTick++;
  rINTPND = 1<<14;
}

