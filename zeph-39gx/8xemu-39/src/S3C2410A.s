;******************************************************************************
;   S3C2410A �����ļ� by ZephRay 2016/1/25
;******************************************************************************
        MODULE  ?cstartup

#include "config.inc"

;����֮����õ���SECTION��ע�������Ǻ�ICF��Ӧ��
        SECTION IRQ_STACK:DATA:NOROOT(3)
        SECTION ABT_STACK:DATA:NOROOT(3)
        SECTION SVC_STACK:DATA:NOROOT(3)
        SECTION UND_STACK:DATA:NOROOT(3)
        SECTION FIQ_STACK:DATA:NOROOT(3)
        SECTION CSTACK:DATA:NOROOT(3)
                
;����section .intvec���ο�ICF���.intvec��Section����0��ַ
        SECTION .intvec:CODE:NOROOT(2)

        PUBLIC  __vector
        PUBLIC  __iar_program_start
        
        ARM

;�ж�����������ʹ�þ��Ե�ַ��ÿ������ռ��4���ֽ�
;û���õ����������˿գ����Բ���ʵ��
__vector:
                B Reset_Handler        ;��λ����������������תִ�и�λ����
                B .                    ;δ����ָ��(Undefined instruction)
                B .                    ;����ж�(SWI)
                B .                    ;Ԥ��ȡʧ��(Prefetch abort)
                B .                    ;����ʧ��(Data abort)
                B .                    ;����(Reserved) 
                B IRQ_Handler          ;IRQ�ж�
                B .                    ;FIQ�ж�


;����section .text
        SECTION .text:CODE:NOROOT(2)

        EXTERN  ?main
        REQUIRE __vector

        ARM

;IRQ�жϴ�����ȡ��ǰ�ж�Դ��Ȼ���λ��HandleEINT0��ʼ���ж�������Ȼ��ִ��ISR
IRQ_Handler
                SUB	sp,sp,#4       ;reserved for PC
	        STMFD	sp!,{r8-r9}
                
	        LDR	r9,=INTOFFSET
	        LDR	r9,[r9]
	        LDR	r8,=HandleEINT0
	        ADD	r8,r8,r9,lsl #2
	        LDR	r8,[r8]
	        STR	r8,[sp,#8]
	        LDMFD	sp!,{r8-r9,pc}                
                

;�������ó�ʼ���ڴ������
                IF      MC_SETUP <> 0
MC_CFG
                DCD     BWSCON_Val
                DCD     BANKCON0_Val
                DCD     BANKCON1_Val
                DCD     BANKCON2_Val
                DCD     BANKCON3_Val
                DCD     BANKCON4_Val
                DCD     BANKCON5_Val
                DCD     BANKCON6_Val
                DCD     BANKCON7_Val
                DCD     REFRESH_Val
                DCD     BANKSIZE_Val
                DCD     MRSRB6_Val
                DCD     MRSRB7_Val
                ENDIF


;��ʼ��ʱ�ӿ�����
                IF      CLK_SETUP <> 0
CLK_CFG
                DCD     LOCKTIME_Val     
                DCD     CLKDIVN_Val 
                DCD     MPLLCON_Val 
                DCD     UPLLCON_Val 
                DCD     CLKSLOW_Val 
                DCD     CLKCON_Val 
                ENDIF 


;��λ�����������ĳ�������￪ʼִ��
__iar_program_start:
?cstartup:

                EXPORT  Reset_Handler
Reset_Handler   

                IF      WT_SETUP <> 0
                LDR     R0, =WT_BASE
                LDR     R1, =WTCON_Val
                LDR     R2, =WTDAT_Val
                STR     R2, [R0, #WTCNT_OFS]
                STR     R2, [R0, #WTDAT_OFS]
                STR     R1, [R0, #WTCON_OFS]
                ENDIF
                
                
                IF      CLK_SETUP <> 0         
                LDR     R0, =CLK_BASE            
                ADR     R8, CLK_CFG
                LDMIA   R8, {R1-R6}            
                STR     R1, [R0, #LOCKTIME_OFS]
                STR     R2, [R0, #CLKDIVN_OFS]  
                STR     R3, [R0, #MPLLCON_OFS] 
                STR     R4, [R0, #UPLLCON_OFS]  
                STR     R5, [R0, #CLKSLOW_OFS]
                STR     R6, [R0, #CLKCON_OFS]
                ENDIF                          

               	                  
                IF      MC_SETUP <> 0
                ADR     R14, MC_CFG
                LDMIA   R14, {R0-R12}
                LDR     R14, =MC_BASE
                STMIA   R14, {R0-R12}
                ENDIF          	              
                                
              
                IF      PIO_SETUP <> 0
                LDR     R14, =PIO_BASE

                IF      PIOA_SETUP <> 0
                LDR     R0, =PCONA_Val
                STR     R0, [R14, #PCONA_OFS]
                ENDIF

                IF      PIOB_SETUP <> 0
                LDR     R0, =PCONB_Val
                LDR     R1, =PUPB_Val
                STR     R0, [R14, #PCONB_OFS]
                STR     R1, [R14, #PUPB_OFS]
                ENDIF

                IF      PIOC_SETUP <> 0
                LDR     R0, =PCONC_Val
                LDR     R1, =PUPC_Val
                STR     R0, [R14, #PCONC_OFS]
                STR     R1, [R14, #PUPC_OFS]
                ENDIF

                IF      PIOD_SETUP <> 0
                LDR     R0, =PCOND_Val
                LDR     R1, =PUPD_Val
                STR     R0, [R14, #PCOND_OFS]
                STR     R1, [R14, #PUPD_OFS]
                ENDIF

                IF      PIOE_SETUP <> 0
                LDR     R0, =PCONE_Val
                LDR     R1, =PUPE_Val
                STR     R0, [R14, #PCONE_OFS]
                STR     R1, [R14, #PUPE_OFS]
                ENDIF

                IF      PIOF_SETUP <> 0
                LDR     R0, =PCONF_Val
                LDR     R1, =PUPF_Val
                STR     R0, [R14, #PCONF_OFS]
                STR     R1, [R14, #PUPF_OFS]
                ENDIF

                IF      PIOG_SETUP <> 0
                LDR     R0, =PCONG_Val
                LDR     R1, =PUPG_Val
                STR     R0, [R14, #PCONG_OFS]
                STR     R1, [R14, #PUPG_OFS]
                ENDIF
  
                IF      PIOH_SETUP <> 0
                LDR     R0, =PCONH_Val
                LDR     R1, =PUPH_Val
                STR     R0, [R14, #PCONH_OFS]
                STR     R1, [R14, #PUPH_OFS]
                ENDIF
               
                ENDIF
                
                ;Ϊÿ��ģʽ����ջ�ռ�

                MRS     r0, cpsr                ; Original PSR value

                BIC     r0, r0, #MODE_BITS      ; Clear the mode bits
                ORR     r0, r0, #ABT_MODE       ; Set ABT mode bits
                MSR     cpsr_c, r0              ; Change the mode
                LDR     sp, =SFE(ABT_STACK)     ; End of ABT_STACK

                BIC     r0, r0, #MODE_BITS      ; Clear the mode bits
                ORR     r0, r0, #SVC_MODE       ; Set SVC mode bits
                MSR     cpsr_c, r0              ; Change the mode
                LDR     sp, =SFE(SVC_STACK)     ; End of SVC_STACK

                BIC     r0, r0, #MODE_BITS      ; Clear the mode bits
                ORR     r0, r0, #UND_MODE       ; Set UND mode bits
                MSR     cpsr_c, r0              ; Change the mode
                LDR     sp, =SFE(UND_STACK)     ; End of UND_STACK

                BIC     r0, r0, #MODE_BITS      ; Clear the mode bits
                ORR     r0, r0, #FIQ_MODE       ; Set FIQ mode bits
                MSR     cpsr_c, r0              ; Change the mode
                LDR     sp, =SFE(FIQ_STACK)     ; End of FIQ_STACK

                BIC     r0, r0, #MODE_BITS      ; Clear the mode bits
                ORR     r0, r0, #IRQ_MODE       ; Set IRQ mode bits
                MSR     cpsr_c, r0              ; Change the mode
                LDR     sp, =SFE(IRQ_STACK)     ; End of IRQ_STACK

                BIC     r0 ,r0, #MODE_BITS      ; Clear the mode bits
                ORR     r0 ,r0, #SYS_MODE       ; Set System mode bits
                MSR     cpsr_c, r0              ; Change the mode
                LDR     sp, =SFE(CSTACK)        ; End of CSTACK


                ;ִ��IAR�����ݳ�ʼ������
                
                LDR     r0,=?main
                BX      r0

                B .

                END
