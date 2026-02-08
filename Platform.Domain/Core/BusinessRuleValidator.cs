using System;
using System.Collections.Generic;
using System.Linq;

namespace Platform.Domain.Core
{
    /// <summary>
    /// 业务规则验证器
    /// 这是一个纯函数实现，不产生副作用
    /// </summary>
    public static class BusinessRuleValidator
    {
        /// <summary>
        /// 验证实体是否符合指定的业务规则
        /// </summary>
        /// <typeparam name="T">实体类型</typeparam>
        /// <param name="entity">要验证的实体</param>
        /// <param name="rules">业务规则集合</param>
        /// <returns>验证结果</returns>
        public static ValidationResult ValidateEntity<T>(T entity, IEnumerable<BusinessRule<T>> rules)
        {
            var errors = new List<string>();
            
            foreach (var rule in rules)
            {
                if (!rule.Condition(entity))
                {
                    errors.Add(rule.ErrorMessage);
                }
            }
            
            return errors.Any() 
                ? ValidationResult.Failure(errors) 
                : ValidationResult.Success;
        }
        
        /// <summary>
        /// 计算下一个状态
        /// </summary>
        /// <typeparam name="T">状态类型</typeparam>
        /// <param name="currentState">当前状态</param>
        /// <param name="command">命令</param>
        /// <returns>新状态</returns>
        public static T CalculateNextState<T>(T currentState, Func<T, T> command)
        {
            return command(currentState);
        }
    }
    
    /// <summary>
    /// 业务规则类
    /// </summary>
    /// <typeparam name="T">实体类型</typeparam>
    public class BusinessRule<T>
    {
        public Func<T, bool> Condition { get; set; }
        public string ErrorMessage { get; set; }
        
        public BusinessRule(Func<T, bool> condition, string errorMessage)
        {
            Condition = condition;
            ErrorMessage = errorMessage;
        }
    }
}