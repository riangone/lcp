using Dapper;
using Platform.Infrastructure.Data;
using Platform.Infrastructure.Definitions;
using System;
using System.Collections.Generic;
using System.Data;
using System.Text.Json;
using System.Threading.Tasks;

namespace Platform.Infrastructure.Shell
{
    /// <summary>
    /// 快照仓库实现
    /// </summary>
    public class SnapshotRepository : ISnapshotRepository
    {
        private readonly IDbConnection _db;

        public SnapshotRepository(DbConnectionFactory factory)
        {
            _db = factory.Create();
        }

        public async Task<string> SaveSnapshotAsync(Snapshot snapshot)
        {
            var jsonProvenance = JsonSerializer.Serialize(snapshot.Provenance);
            var jsonData = JsonSerializer.Serialize(snapshot.Data);

            var sql = @"
                INSERT INTO Snapshots (Id, ModelType, Data, Provenance, CreatedAt, Status, ApprovedAt, ApprovedBy) 
                VALUES (@Id, @ModelType, @Data, @Provenance, @CreatedAt, @Status, @ApprovedAt, @ApprovedBy)";

            await _db.ExecuteAsync(sql, new
            {
                Id = snapshot.Id,
                ModelType = snapshot.ModelType,
                Data = jsonData,
                Provenance = jsonProvenance,
                CreatedAt = snapshot.CreatedAt,
                Status = snapshot.Status.ToString(),
                ApprovedAt = snapshot.ApprovedAt,
                ApprovedBy = snapshot.ApprovedBy
            });

            return snapshot.Id;
        }

        public async Task<Snapshot?> GetSnapshotAsync(string id)
        {
            var sql = "SELECT * FROM Snapshots WHERE Id = @Id";
            var record = await _db.QueryFirstOrDefaultAsync<SnapshotRecord>(sql, new { Id = id });

            return record != null ? MapToSnapshot(record) : null;
        }

        public async Task<List<Snapshot>> GetPendingSnapshotsAsync(string modelType)
        {
            var sql = "SELECT * FROM Snapshots WHERE ModelType = @ModelType AND Status = 'Pending'";
            var records = await _db.QueryAsync<SnapshotRecord>(sql, new { ModelType = modelType });

            return records.AsList().ConvertAll(MapToSnapshot);
        }

        public async Task UpdateSnapshotStatusAsync(string id, SnapshotStatus status, string? approvedBy = null)
        {
            DateTime? approvedAt = null;
            if (status == SnapshotStatus.Approved)
            {
                approvedAt = DateTime.UtcNow;
            }

            var sql = "UPDATE Snapshots SET Status = @Status, ApprovedAt = @ApprovedAt, ApprovedBy = @ApprovedBy WHERE Id = @Id";
            await _db.ExecuteAsync(sql, new
            {
                Id = id,
                Status = status.ToString(),
                ApprovedAt = approvedAt,
                ApprovedBy = approvedBy
            });
        }

        private static Snapshot MapToSnapshot(SnapshotRecord record)
        {
            return new Snapshot
            {
                Id = record.Id,
                ModelType = record.ModelType,
                Data = JsonSerializer.Deserialize<object>(record.Data),
                Provenance = JsonSerializer.Deserialize<Provenance>(record.Provenance),
                CreatedAt = record.CreatedAt,
                ApprovedAt = record.ApprovedAt,
                ApprovedBy = record.ApprovedBy,
                Status = Enum.Parse<SnapshotStatus>(record.Status)
            };
        }
    }

    internal class SnapshotRecord
    {
        public string Id { get; set; } = string.Empty;
        public string ModelType { get; set; } = string.Empty;
        public string Data { get; set; } = string.Empty;
        public string Provenance { get; set; } = string.Empty;
        public DateTime CreatedAt { get; set; }
        public DateTime? ApprovedAt { get; set; }
        public string? ApprovedBy { get; set; }
        public string Status { get; set; } = string.Empty;
    }
}