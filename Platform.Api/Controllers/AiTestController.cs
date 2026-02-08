using Microsoft.AspNetCore.Mvc;
using Platform.Api.TestScenarios;
using System.Threading.Tasks;

namespace Platform.Api.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class AiTestController : ControllerBase
    {
        private readonly AiArchitectureTestScenario _testScenario;

        public AiTestController(AiArchitectureTestScenario testScenario)
        {
            _testScenario = testScenario;
        }

        [HttpPost("artist-optimization")]
        [HttpGet("artist-optimization")]  // 添加GET支持用于测试
        public async Task<IActionResult> RunArtistOptimizationScenario()
        {
            string logOutput = "";
            
            // 重定向控制台输出到字符串
            using (var writer = new System.IO.StringWriter())
            {
                var originalOutput = System.Console.Out;
                System.Console.SetOut(writer);
                
                try
                {
                    await _testScenario.ExecuteArtistOptimizationScenario();
                    logOutput = writer.ToString();
                }
                finally
                {
                    System.Console.SetOut(originalOutput);
                }
            }
            
            return Ok(logOutput);
        }

        [HttpPost("invoice-prediction")]
        [HttpGet("invoice-prediction")]  // 添加GET支持用于测试
        public async Task<IActionResult> RunInvoicePredictionScenario()
        {
            string logOutput = "";
            
            // 重定向控制台输出到字符串
            using (var writer = new System.IO.StringWriter())
            {
                var originalOutput = System.Console.Out;
                System.Console.SetOut(writer);
                
                try
                {
                    await _testScenario.ExecuteInvoicePredictionScenario();
                    logOutput = writer.ToString();
                }
                finally
                {
                    System.Console.SetOut(originalOutput);
                }
            }
            
            return Ok(logOutput);
        }

        [HttpPost("all")]
        [HttpGet("all")]  // 添加GET支持用于测试
        public async Task<IActionResult> RunAllScenarios()
        {
            string logOutput = "";
            
            // 重定向控制台输出到字符串
            using (var writer = new System.IO.StringWriter())
            {
                var originalOutput = System.Console.Out;
                System.Console.SetOut(writer);
                
                try
                {
                    await _testScenario.ExecuteArtistOptimizationScenario();
                    await _testScenario.ExecuteInvoicePredictionScenario();
                    logOutput = writer.ToString();
                }
                finally
                {
                    System.Console.SetOut(originalOutput);
                }
            }
            
            return Ok(logOutput);
        }
    }
}